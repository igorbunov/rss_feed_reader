<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeployCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $deployCommands = [
            'git pull',
            'composer install',
            'npm install',
            'php artisan migrate --force --no-interaction'
        ];

        exec(implode(' & ', $deployCommands));

        $clearCacheCommands = [
            'php artisan cache:clear',
            'php artisan route:clear',
            'php artisan config:clear',
            'php artisan view:clear',
        ];

        exec(implode(' & ', $clearCacheCommands));

        info('deploy done');
    }
}
