<?php

namespace App\Console;

use Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\TdoTableTransfer::class,
        \App\Console\Commands\TgdTableTransfer::class,
        \App\Console\Commands\TgdOrderTransfer::class,
        \App\Console\Commands\TgdActivityTransfer::class,
        \App\Console\Commands\TgdMgpayrecordTransfer::class,
        \App\Console\Commands\updateMonthtable::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            \Log::info('-e php artisan schedule:run');
        })->everyMinute();

//        $schedule->call(function () {
//            Artisan::queue('tdo:trans', ['table' => 'all']);
//        })->hourlyAt(15);

        $schedule->call(function () {
            Artisan::queue('tgd:updateConfigurationTable');
        })->daily();

//        $schedule->call(function () {
//            Artisan::queue('tgd:transfer', ['table' => 'all']);
//        })->hourlyAt(15);
//
//        $schedule->call(function () {
//            Artisan::queue('tgd:orderTransfer', ['table' => 'all']);
//        })->hourlyAt(15);
//
//        $schedule->call(function () {
//            Artisan::queue('tgd:activityTransfer', ['table' => 'all']);
//        })->hourlyAt(15);
//
//        $schedule->call(function () {
//            Artisan::queue('tgd:mgpayrecordTransfer', ['table' => 'all']);
//        })->hourlyAt(15);
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
