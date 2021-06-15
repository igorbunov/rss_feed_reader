<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedResult;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FeedResultController extends Controller
{
    protected const PAGES_PER_PAGE = 50;

    public function index(Request $request)
    {
        $allFeeds = Feed::all();
        $feedId = (int) $request->get('feed', 0);

        $results = FeedResult::with('feed')
            ->whereHas('feed', function($query) use ($feedId) {
                if ($feedId > 0) {
                    $query->where('id', $feedId);
                }
            })
            ->latest('updated_at')
            ->paginate(self::PAGES_PER_PAGE);

        $results = $this->addFeedAsPrefix($results, $feedId, $allFeeds);

        $this->setResultsAsWatched($results);

        return view('home', compact('allFeeds', 'results', 'feedId'));
    }

    protected function addFeedAsPrefix($results, $feedId, $allFeeds)
    {
        if (empty($feedId) and $allFeeds->count() > 1) {
            foreach ($results as &$record) {
                $record->prefix = '[' . Str::limit($record->feed->name, 10) . ']';
            }

            unset($record);
        }

        return $results;
    }

    protected function setResultsAsWatched($results)
    {
        $ids = [];

        foreach ($results as $record) {
            if (empty($record->is_watched)) {
                $ids[] = $record->id;
            }
        }

        if (!empty($ids)) {
            FeedResult::whereIn('id', $ids)->update([
                'is_watched' => 1
            ]);
        }
    }
}
