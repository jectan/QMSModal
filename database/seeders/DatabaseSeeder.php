<?php

namespace Database\Seeders;

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
        $this->call(CallerTypeSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(OfficeSeeder::class);
        $this->call(BarangaySeeder::class);
        $this->call(SeriesSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(UnitDivSeeder::class);
      
    }
}
