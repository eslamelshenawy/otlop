<?php

use Illuminate\Database\Seeder;

class ContactInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ContactInformation::create([
            'email'=>'',
            'phone'=>'',
            'address'=>'',
            'mobile'=>'',
            'support_call'=>'',
        ]);
    }
}
