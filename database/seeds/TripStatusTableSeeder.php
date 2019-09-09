<?php

use Illuminate\Database\Seeder;

class TripStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\TripStatus::create([
            'title'=>'Not Yet Searching For Drivers',
        ]);
        \App\TripStatus::create([
            'title'=>'Searching For A Driver',
        ]);
        \App\TripStatus::create([
            'title'=>'Driver Accepted',
        ]);
        \App\TripStatus::create([
            'title'=>'Could Not Find A Driver',
        ]);
        \App\TripStatus::create([
            'title'=>'Trip Canceled',
        ]);
    }
}
