<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of admin data
        $admins = [
            [
                'name' => 'Admin One',
                'email' => 'admin1@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => bcrypt('password'),
                'is_super' => 1,
            ],

        ];
    
        // Loop through each admin and create them
        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
    
}
