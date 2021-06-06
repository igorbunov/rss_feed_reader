<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\FeedResult;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LoadFeedsResultsJob
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
        $feeds = Feed::withoutGlobalScopes()
            ->whereHas('user')
            ->whereNull('deleted_at')
            ->get();

        foreach ($feeds as $feed) {
            try {
                $loadedCount = $this->loadFeedResults($feed);

                info("Feed #{$feed->id}: loaded {$loadedCount}");
            } catch (\Exception $err) {
                info('Error load feed results: ' . $err->getMessage());
            }
        }
    }

    protected function loadFeedResults(Feed $feed): int
    {
        $result = file_get_contents($feed->url);
        $counter = 0;

        if (is_null($result)) {
            return $counter;
        }

        $xml = simplexml_load_string($result);

        if (is_null($xml)) {
            return $counter;
        }

        foreach ($xml->channel->item as $item) {
            $link = strval($item->link);
            $title = strval($item->title);

            if (empty($link) or empty($title)) {
                continue;
            }

            FeedResult::firstOrCreate(
                [
                    'feed_id' => $feed->id,
                    'link' => $link
                ],
                [
                    'title' => $title
                ]
            );

            $counter++;
        }

        return $counter;
    }
}
