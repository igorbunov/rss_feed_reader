<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Feed;
use App\Models\User;
use App\Models\FeedResult;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        \App\Models\User::factory(1)->create();

        $this->user = User::first();
    }

    public function testGuestRedirectToLogin()
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('login'));
    }

    public function testLoggedInUserSeeHomepage()
    {
        $this->actingAs($this->user);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function testSeeResults()
    {
        $this->actingAs($this->user);

        $feed = Feed::create([
            'user_id' => auth()->id(),
            'name' => 'test feed',
            'url' => 'http://google.com/',
            'is_notify' => 0
        ]);

        $result = FeedResult::create(
            [
                'feed_id' => $feed->id,
                'link' => 'http://google.com/rss/some-news',
                'title' => 'Some feed result',
                'is_watched' => 0
            ]
        );

        $response = $this->get('/');

        $response->assertSee('Some feed result');

    }
}
