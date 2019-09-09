<?php

use Illuminate\Database\Seeder;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pageAbout= \App\Page::create([
            'slug' =>'about-us',
        ]);

        $pageTerm = \App\Page::create([
            'slug' =>'terms',
        ]);

        $pageContact = \App\Page::create([
            'slug' =>'contact-us',
        ]);

        \App\PageTranslation::create([
            'page_id'=> $pageAbout->id,
            'name'=>'About Us',
            'description'=>'',
            'locale'   =>'en',
        ]);
        \App\PageTranslation::create([
            'page_id'=> $pageAbout->id,
            'name'=>'من نحن',
            'description'=>'',
            'locale'   =>'ar',
        ]);

        \App\PageTranslation::create([
            'page_id'=> $pageTerm->id,
            'name'=>'Terms & Conditions',
            'description'=>'',
            'locale'   =>'en',
        ]);
        \App\PageTranslation::create([
            'page_id'=> $pageTerm->id,
            'name'=>'الشروط و الأحكام',
            'description'=>'',
            'locale'   =>'ar',
        ]);

        \App\PageTranslation::create([
            'page_id'=> $pageContact->id,
            'name'=>'Contact Us',
            'description'=>'',
            'locale'   =>'en',
        ]);
        \App\PageTranslation::create([
            'page_id'=> $pageContact->id,
            'name'=>'اتصل بنا',
            'description'=>'',
            'locale'   =>'ar',
        ]);
    }
}
