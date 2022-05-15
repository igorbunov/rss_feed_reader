<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\User;
use App\Models\FeedResult;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\RSSFeedReportNotification;

class NotifyAboutNewFeedResultsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lengthInMinutes;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $lengthInMinutes = 60)
    {
        $this->lengthInMinutes = $lengthInMinutes;
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

                $user->notify(new RSSFeedReportNotification($feed, $data->cnt, $this->getLatestFeeds($feed)));
            } catch (\Exception $err) {
                info('error in notification: ' . $err->getMessage());
            }
        }
    }

    protected function getLatestFeeds(Feed $feed)
    {
        return FeedResult::where('feed_id', $feed->id)
            ->whereRaw("created_at + interval {$this->lengthInMinutes} minute > now()")
            ->get();
    }

    protected function getNewResultsCount(): array
    {
        return DB::select(
            "select f.id, f.user_id, a.cnt
            from feeds f
            inner join (
                select feed_id, count(1) as cnt
                from feed_results
                where created_at + interval {$this->lengthInMinutes} minute > now()
                group by feed_id
            )a on f.id = a.feed_id
            where f.is_notify = 1 and f.deleted_at is null"
        );
    }
}
