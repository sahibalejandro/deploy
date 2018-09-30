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
        User::create([
            'name' => 'Sahib',
            'email' => 'sahib.alejandro@gmail.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
