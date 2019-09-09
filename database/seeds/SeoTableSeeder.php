<?php

use Illuminate\Database\Seeder;

class SeoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $seo = \App\Seo::create([
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);

        \App\SeoTranslation::create([
            'seo_id'=> $seo->id,
            'keyword' => 'keyword ',
            'description' => 'description',
            'locale'=>'en'
        ]);

        \App\SeoTranslation::create([
            'seo_id'=> $seo->id,
            'keyword' => 'الكلمات',
            'description' => 'الوصف',
            'locale'=>'ar'
        ]);
    }
}
