<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();
        \App\Models\User::factory(1)->create(['email' => 'admin2@admin.com']);
        \App\Models\Feed::factory(6)->create();
        \App\Models\FeedResult::factory(100)->create();
    }
}
