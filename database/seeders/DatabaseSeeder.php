<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            // PermissionSeeder::class,  // Temporarily disabled
            // RolePermissionSeeder::class,  // Temporarily disabled
            // SampleDataSeeder::class, // Commented out to use CategoryProductSeeder instead
            CategoryProductSeeder::class, // This will create categories and products with real data
        ]);
    }
}
