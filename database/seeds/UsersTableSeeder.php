<?php

use App\User;
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
        factory(User::class)
            ->create([
                'name' => 'admin',
                'email' => 'admin@code95.com',
                'password' => 'password'
            ]);

        factory(User::class, 500)
            ->create();
    }
}
