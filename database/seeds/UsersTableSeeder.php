<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'dotuancd@gmail.com',
            'name' => 'Admin',
            'password' => bcrypt('123456'),
            'api_token' => str_random(64)
        ]);
    }
}
