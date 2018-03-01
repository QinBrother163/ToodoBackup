<?php

namespace App\Console\Commands;

use App\TdoTrans104Table;
use App\TdoTransTable;
use DB;
use Illuminate\Console\Command;

class updateMonthtable extends Command
{

    protected $signature = 'tgd:updateConfigurationTable';
    protected $description = 'Update the configuration table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $YM = date('Ym');
        $YM = date('Ym', strtotime($YM));

        $fsdp_activity_mgpayrecord = 'list_' . $YM;
        $order = 'order_' . $YM;

        $log = TdoTransTable::where(['name' => $order])->first();
        if (empty($log)) {
            $log = new TdoTransTable();
            $log->fill([
                'name' => $order,
                'field' => 'update_time',
                'primary' => 'order_id',
                'verify' => '1',
            ]);
            $log->save();
        }

        $olog = TdoTrans104Table::where(['name' => $fsdp_activity_mgpayrecord])->first();
        if (empty($olog)) {

            DB::update('update tdo_trans104_tables set name = ? where id = ?',[$fsdp_activity_mgpayrecord,1]);

        }

    }
}
