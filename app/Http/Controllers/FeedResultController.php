<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedResult;
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

        return view('home', compact('allFeeds', 'results', 'feedId'));
    }
}
