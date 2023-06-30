<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
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
        // \App\Models\User::factory(10)->create();

        // Admin::create([
        //     'name' => 'Administrator',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('admin123')
        // ]);

        User::create([
            'name' => 'Jawahiir',
            'lastname' => 'Abdillah',
            'photo' => '1',
            'email' => 'abdi@gmail.com',
            'password' => bcrypt('abdi123')
        ]);
    }
}
