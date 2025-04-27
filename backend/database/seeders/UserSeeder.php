<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admUser = User::where('email', 'admin@mail.com')->first();

        if (is_null($admUser)) {
            User::factory()->create([
                'name' => 'ADMIN',
                'email' => 'admin@mail.com',
                'password' => 'password'
            ]);
        }

        User::factory(3)->create();

    }
}
