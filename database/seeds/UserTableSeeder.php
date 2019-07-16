<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$admin_user 					= new \App\User;
    	$admin_user->name 				= "InterNations";
    	$admin_user->email 				= "admin@internations.com";
        $admin_user->email_verified_at 	= now();
    	$admin_user->password 			= bcrypt("secret");
    	$admin_user->remember_token		= Str::random(10);
    	$admin_user->user_type 			= "admin";
    	$admin_user->save();

        factory(\App\User::class, 5)->create();
    }
}
