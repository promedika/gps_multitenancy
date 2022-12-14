<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Multitenancy\Models\Tenant;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (Tenant::checkCurrent()) {
            $this->call([
                // NursePermissionSeeder::class,
                PermissionSeeder::class,
                // UserTableSeeder::class
            ]);
        }

        // \App\Models\User::factory(10)->create();
    }
}
