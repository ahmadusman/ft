<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $statuses=["Just created","Accepted by admin","Accepted by restaurant","Assigned to driver","Prepared","Picked up","Delivered","Rejected by admin","Rejected by restaurant"];
        foreach ( $statuses as $key => $status) {
            DB::table('status')->insert([
                'name' => $status,
                'alias' =>  str_replace(" ","_",strtolower($status)),
            ]);
        }
    }
}
