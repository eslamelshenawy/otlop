<?php

use Illuminate\Database\Seeder;

class DayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seo = \App\Day::create([
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo->id,
            'name' => 'Sunday',
            'locale'=>'en'
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo->id,
            'name' => 'الاحد',
            'locale'=>'ar'
        ]);

        $seo1 = \App\Day::create([
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo1->id,
            'name' => 'Monday',
            'locale'=>'en'
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo1->id,
            'name' => 'الاثنين',
            'locale'=>'ar'
        ]);

        $seo2 = \App\Day::create([
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo2->id,
            'name' => 'Tuesday',
            'locale'=>'en'
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo2->id,
            'name' => 'الثلاثاء',
            'locale'=>'ar'
        ]);

        $seo3 = \App\Day::create([
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo3->id,
            'name' => 'Wednesday',
            'locale'=>'en'
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo3->id,
            'name' => 'الاربعاء',
            'locale'=>'ar'
        ]);
        $seo4= \App\Day::create([
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo4->id,
            'name' => 'Thursday',
            'locale'=>'en'
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo4->id,
            'name' => 'الخميس',
            'locale'=>'ar'
        ]);

        $seo5= \App\Day::create([
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo5->id,
            'name' => 'Friday',
            'locale'=>'en'
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo5->id,
            'name' => 'الجمعة',
            'locale'=>'ar'
        ]);

        $seo5= \App\Day::create([
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo5->id,
            'name' => 'Saturday',
            'locale'=>'en'
        ]);

        \App\DayTranslation::create([
            'day_id'=> $seo5->id,
            'name' => 'السبت',
            'locale'=>'ar'
        ]);




    }
}
