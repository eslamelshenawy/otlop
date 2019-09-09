<?php

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
         $this->call(LaratrustSeeder::class);
         $this->call(AdminTableSeeder::class);
         $this->call(ContactInformationSeeder::class);
         $this->call(SeoTableSeeder::class);
         $this->call(SettingsTableSeeder::class);
         $this->call(PageTableSeeder::class);
         $this->call(DayTableSeeder::class);
         $this->call(TripStatusTableSeeder::class);
        $this->call(TripDriverStatusTableSeeder::class);
    }
}
