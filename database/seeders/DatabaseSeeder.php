<?php

namespace Database\Seeders;

use App\Helpers\Constants;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::withoutEvents(function ()  {
            User::factory()->create([
                'name' => 'Administrator',
                'email' => 'admin@laravel.com',
                'roles' => [Constants::ROLE_ADMIN],
                'status' => true
            ]);
        });

        User::factory()->create([
            'email' => 'user@laravel.com',
            'roles' => [],
            'status' => true
        ]);
    }
}
