<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $setting =  \App\Setting::create([
            'logo'=>'',
            'icon'=>'',
            'copyright'=>'',
            'email'=>'',
            'lang'=>'en',

        ]);
       \App\SettingTranslation::create([
           'setting_id'=>$setting->id,
           'name'=>'site name english english',
           'description'=>'description site english english',
            'locale'   =>'en',
           ]);

        \App\SettingTranslation::create([
            'setting_id'=>$setting->id,
            'name'=>'site name english arabic',
            'description'=>'description site english arabic',
            'locale'   =>'ar',
        ]);

    }
}
