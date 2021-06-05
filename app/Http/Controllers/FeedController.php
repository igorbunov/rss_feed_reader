<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\CreateFeedRequest;

class FeedController extends Controller
{
    protected const PAGES_PER_PAGE = 10;

    public function index()
    {
        $feeds = Feed::latest()->paginate(self::PAGES_PER_PAGE);

        return view('feed.index', compact('feeds'));
    }

    public function create()
    {
        return view('feed.create');
    }

    public function store(CreateFeedRequest $request)
    {
        Feed::create($request->validated() + [
            'user_id' => auth()->id()
        ]);

        return redirect()->route('feed.index')->withStatus('Record added succesfully');
    }

    public function edit(Feed $feed)
    {
        return view('feed.edit', compact('feed'));
    }

    public function update(StoreFeedRequest $request, Feed $feed)
    {
        $feed->update($request->validated());

        return redirect()->route('feed.index')->withStatus('Record updated succesfully');
    }

    public function destroy(Feed $feed)
    {
        $feed->delete();

        return redirect()->route('feed.index')->withStatus('Record deleted succesfully');
    }
}
