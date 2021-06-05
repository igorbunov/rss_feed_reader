<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\User;
use App\Notifications\NewFeedResultsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyAboutNewFeedResultsJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lentthInMinutes;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $lentthInMinutes = 60)
    {
        $this->lentthInMinutes = $lentthInMinutes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dataToNotify = $this->getNewResultsCount();

        foreach ($dataToNotify as $data) {
            try {
                $user = User::find($data->user_id);
                $feed = Feed::withoutGlobalScopes()->find($data->id);

                $user->notify(new NewFeedResultsNotification($feed, $data->cnt));
            } catch (\Exception $err) {
                info('error in notification: ' . $err->getMessage());
            }
        }
    }

    protected function getNewResultsCount(): array
    {
        return DB::select(
            "select f.id, f.user_id, a.cnt
            from feeds f
            inner join (
                select feed_id, count(1) as cnt
                from feed_results
                where created_at + interval {$this->lentthInMinutes} minute > now()
                group by feed_id
            )a on f.id = a.feed_id
            where f.is_notify = 1 and f.deleted_at is null"
        );
    }
}
