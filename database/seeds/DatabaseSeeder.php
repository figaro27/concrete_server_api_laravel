<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * use Illuminate\Support\Str;

     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            'id' => Str::uuid()->toString(),
            'firstname' => 'admin',
            'lastname' => '',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => '5'
        ]);
    }
}
