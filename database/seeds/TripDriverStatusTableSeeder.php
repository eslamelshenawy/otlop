<?php

use Illuminate\Database\Seeder;

class TripDriverStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\TripDriverStatus::create([
            'title'=>'Not Yet Taken',
        ]);
        \App\TripDriverStatus::create([
            'title'=>'Taken',
        ]);
        \App\TripDriverStatus::create([
            'title'=>'End',
        ]);
    }
}
