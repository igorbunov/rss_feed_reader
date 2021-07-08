<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeedTest extends TestCase
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
        $response = $this->get(route('feed.index'));

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('login'));
    }

    public function testLoggedUserSeeFeedsPage()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('feed.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Notify');
    }

    public function testGuestNotSeeCreateFeedPage()
    {
        $response = $this->get(route('feed.create'));

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('login'));
    }

    public function testLoggedUserSeeCreateFeedPage()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('feed.create'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Once in hour');
    }

    public function testCreateNewFeed()
    {
        $this->actingAs($this->user);

        $this->get(route('feed.create'));

        $response = $this->post(route('feed.store', [
            'name' => 'test feed',
            'url' => 'http://google.com/',
            'is_notify' => 1
        ]));

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('feeds', [
            'name' => 'test feed',
            'url' => 'http://google.com/',
            'is_notify' => 1
        ]);
    }

    public function testCreateNewWithoutName()
    {
        $this->actingAs($this->user);

        $this->get(route('feed.create'));

        $response = $this->post(route('feed.store', [
            'url' => 'http://google.com/',
            'is_notify' => 1
        ]));

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
    }

    public function testCreateNewWithWrongUrl()
    {
        $this->actingAs($this->user);

        $this->get(route('feed.create'));

        $response = $this->post(route('feed.store', [
            'name' => 'test feed',
            'url' => 'some stupid url',
            'is_notify' => 0
        ]));

        $response->assertSessionHasErrors([
            'url' => 'The url format is invalid.'
        ]);
    }

    public function testSeeEditFeedForm()
    {
        $this->actingAs($this->user);

        $feed = Feed::create([
            'user_id' => auth()->id(),
            'name' => 'test feed',
            'url' => 'http://google.com/',
            'is_notify' => 0
        ]);

        $response = $this->get(route('feed.edit', $feed));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Edit Feed');
    }

    public function testEditFeed()
    {
        $this->actingAs($this->user);

        $feed = Feed::create([
            'user_id' => auth()->id(),
            'name' => 'test feed',
            'url' => 'http://google.com/',
            'is_notify' => 0
        ]);

        $this->get(route('feed.edit', $feed));

        $response = $this->post(route('feed.update', $feed), [
            '_method' => 'PUT',
            'name' => 'test feed 2',
            'url' => 'http://ya.ru/',
            'is_notify' => 1
        ]);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('feeds', [
            'name' => 'test feed 2',
            'url' => 'http://ya.ru/',
            'is_notify' => 1
        ]);
    }

    public function testDeleteFeed()
    {
        $this->actingAs($this->user);

        $feed = Feed::create([
            'user_id' => auth()->id(),
            'name' => 'test feed',
            'url' => 'http://google.com/',
            'is_notify' => 0
        ]);

        $this->get(route('feed.index'));

        $response = $this->post(route('feed.destroy', $feed), [
            '_method' => 'DELETE'
        ]);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('feeds', [
            'name' => 'test feed',
            'url' => 'http://google.com/',
            'is_notify' => 0,
            'deleted_at' => null
        ]);
    }
}
