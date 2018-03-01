<?php

namespace App\Console\Commands;

use App\TdoSchemaApi;
use App\TdoTransLog;
use App\TdoTransTable;
use Artisan;
use DB;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class TgdOrderTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tgd:orderTransfer {table=all} {field=null} {primary=null} {now=null}';
    protected $description = '172.16.147.103 172.16.147.104 Data transfer';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $table = $this->argument('table');
        $field = $this->argument('field');
        $primary = $this->argument('primary');
        $now = $this->argument('now');

        if ($now == 'null') $now = null;

        if ($table == 'all') {
            if ($field == 'day') {
                $this->allDay($now);
            } else if ($field == 'month') {
                $this->allMonth($now);
            } else {
                $this->all($now);
            }
            return;
        }

        if (empty($field) || $field == 'null') {
            \Log::info("-e tgd:orderTransfer $table field empty!");
            return;
        }
        if ($primary == 'null') $primary = null;

        // 基本执行，每小时一次
        // 遇错后执行，每天定点查询出错
        $this->check($table, $primary, $field, $now);

    }

    protected function allDay($now)
    {
        if (empty($now) || $now == 'null') return;
        $day = date('Y-m-d', strtotime($now));
        for ($hour = 1; $hour <= 24; ++$hour) {
            if ($hour == 24) {
                $now = sprintf('%s 00:15:00', date('Y-m-d', strtotime("$now +1 day")));
            } else {
                $now = sprintf('%s %02d:15:00', $day, $hour);
            }
            Artisan::queue('tgd:orderTransfer', [
                'table' => 'all',
                'field' => null,
                'primary' => null,
                'now' => $now,
            ]);
        }
    }

    protected function allMonth($now)
    {
        if (empty($now) || $now == 'null') return;
        $t = intval(date('t', strtotime($now)));
        if ($t < 28) return;
        $mth = date('Y-m', strtotime($now));
        for ($day = 1; $day <= $t; ++$day) {
            $now = sprintf('%s-%02d', $mth, $day);
            Artisan::queue('tgd:orderTransfer', [
                'table' => 'all',
                'field' => 'day',
                'primary' => null,
                'now' => $now,
            ]);
        }
    }

    protected function all($now)
    {
        // verify==1 是每小时更新的配置
        $tables = TdoTransTable::where('verify', 1)->get();

        foreach ($tables as $table) {
            Artisan::queue('tgd:orderTransfer', [
                'table' => $table->name,
                'field' => $table->field,
                'primary' => $table->primary,
                'now' => $now,
            ]);
        }

    }

    protected function check($table, $primary, $field, $now)
    {
        if (empty($now)) $now = date('Y-m-d H:i:s');
        $time = strtotime("$now -1 hour");

        $date = date('Ymd', $time);
        $log = TdoTransLog::where(['name' => $table, 'date' => $date])->first();
        if (empty($log)) {
            $log = new TdoTransLog();
            $log->fill([
                'name' => $table,
                'date' => $date,
                'flag' => 0,
            ]);
        }
        $begin = date('Y-m-d H', $time);
        $end = date('Y-m-d H', strtotime($now));

        $flag = $log->flag;
        $hour = intval(date('H', $time));
        // $flag == 16777215 表示全部已处理
        if (($flag & (1 << $hour)) > 0) {
            \Log::info("-e tdo:trans $table $field $date $hour already due!");
            return;
        }
        // 传输
        if ($this->trans($table, $primary, $field, $begin, $end)) {
            $log->flag = $flag | (1 << $hour);
            $log->save();
            \Log::info("-e tgd:orderTransfer $table $field $date $hour");
        }
    }

    protected function createIfNotExist($dstDb, $srcDb, $table, $primary = null)
    {
        $dstSm = Schema::setConnection($dstDb);
        if ($dstSm->hasTable($table)) return true;

        $srcSm = Schema::setConnection($srcDb);
        if (!$srcSm->hasTable($table)) return false;

        $manager = $srcDb->getDoctrineSchemaManager();
        $columns = $manager->listTableColumns($table);
        if (empty($columns)) {
            \Log::error("createIfNotExist srcDb has no table $table");
            return false;
        }

        Schema::dropIfExists($table);
        $dstSm->create($table, function (Blueprint $blueprint) use ($primary, $columns) {
            /** @var TdoSchemaApi $api */
            $api = app(TdoSchemaApi::class);
            $api->createColumns($blueprint, $columns, $primary);
        });
        \Log::info("createIfNotExist create table $table primary=$primary");
        return true;
    }

    protected function trans($table, $primary, $field, $begin, $end)
    {
//        \Log::info('trans 1 begin');
        $dstDb = DB::connection('mysql54mg_gd');
        $srcDb = DB::connection('mysql03order');

        // 创建表
        if (!$this->createIfNotExist($dstDb, $srcDb, $table, $primary)) return false;

        $builder = $srcDb->table($table)->whereBetween($field, [$begin, $end]);
        /** @noinspection PhpUnhandledExceptionInspection */
        $dstDb->transaction(function () use ($dstDb, $primary, $builder, $table) {
            $page = 1;
            $perPage = 20;
            do {
                $paginate = $builder->paginate($perPage, ['*'], 'page', $page);
                $curr = $paginate->currentPage();
                $last = $paginate->lastPage();
//                $from = $paginate->firstItem();
//                $to = $paginate->lastItem();
                $items = $paginate->items();

//                $cnt = count($items);
//                \Log::info("translating $curr/$last $cnt($from,$to)");
                if ($primary) {
                    foreach ($items as $item) {
                        $dstDb->table($table)->updateOrInsert(
                            [$primary => $item->$primary],
                            json_decode(json_encode($item), true));
                    }
                } else {
                    $dstDb->table($table)->insert(json_decode(json_encode($items), true));
                }
                if ($curr < $last) $page++;
                else $page = 0;
            } while ($page);
        });
//        \Log::info('trans 2 end');
        return true;
    }
}
