<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManageStaffSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('manage_staff')->insert([
            [
                'full_name' => 'John Smith',
                'email' => 'john.smith@company.com',
                'department' => 'IT',
                'phone' => '+1234567890',
                'address' => '123 Main St, City',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Sarah Johnson',
                'email' => 'sarah.j@company.com',
                'department' => 'HR',
                'phone' => '+0987654321',
                'address' => '456 Oak Ave, Town',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        
        echo "Staff records added successfully!\n";
    }
}