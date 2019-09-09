<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = \App\Admin::create([
            'firstName'=>'super',
            'lastName'=>'super',
            'email'=>'admin@admin.com',
            'password'=>\Illuminate\Support\Facades\Hash::make(123456),
            'status'=>1,
            'userType'=>'super_admin',
            'user_token'=>\Illuminate\Support\Str::random(60),
        ]);
        $admin->attachRole('super_admin');
    }
}
