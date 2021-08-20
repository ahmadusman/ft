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
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(PagesSeeder::class);

        if(env('DEMO_DATA',true)){
            //The demo seader
            $this->call(DemoSeeder::class); 
        }
    }
}
