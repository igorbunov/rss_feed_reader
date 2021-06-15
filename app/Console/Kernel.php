<?php

namespace App\Console;

use App\Jobs\DeployCodeJob;
use App\Jobs\LoadFeedsResultsJob;
use App\Jobs\RemoveOldRecordsJob;
use App\Jobs\NotifyAboutNewFeedResultsJob;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        if (config('app.env') == 'production' and config('app.auto_deployment')) {
            $schedule->call(function () {
                DeployCodeJob::dispatch();
            })->everyFiveMinutes();
        }

        $schedule->call(function () {
            LoadFeedsResultsJob::dispatch();
        })->everyMinute(); // })->everyTwoMinutes();

        $schedule->call(function () {
            NotifyAboutNewFeedResultsJob::dispatch();
        })->hourly();

        $schedule->call(function () {
            RemoveOldRecordsJob::dispatch();
        })->weekly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
