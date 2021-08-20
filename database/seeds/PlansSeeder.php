<?php

use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plan')->insert([
            'name' => 'Free',
            'limit_items'=>20,
            'price'=>0,
            'paddle_id'=>"",
            'description'=>"If you run small restaurant or bar, or just need the basics, this plan is a great.",
            'features'=>"Full access to QR tool, Full access to menu creation tool, Unlimited views, 20 items in menu, Community support",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('plan')->insert([
            'name' => 'Pro',
            'limit_items'=>0,
            'price'=>9,
            'paddle_id'=>"",
            'description'=>"For bigger restaurants and bars. Offer full menu. Limitless plan",
            'features'=>"Full access to QR tool, Full access to menu creation tool, Unlimited views, Unlimited items in menu, Dedicated Support",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('plan')->insert([
            'name' => 'Pro yearly',
            'limit_items'=>0,
            'price'=>87,
            'paddle_id'=>"",
            'period'=>2,
            'description'=>"Same as PRO, with 20% discount. Enjoy your QR menu full year.",
            'features'=>"Full access to QR tool, Full access to menu creation tool, Unlimited views, Unlimited items in menu, Dedicated Support",
            'created_at' => now(),
            'updated_at' => now()
        ]);

       
    }
}
