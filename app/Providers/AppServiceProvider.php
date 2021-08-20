<?php

namespace App\Providers;
use App\Settings;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //ignore default migrations from Cashier
        Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        try {
            \DB::connection()->getPdo();
            $settings=Schema::hasTable('settings')&&Settings::find(1)?Settings::find(1)->toArray():[];

            //Site logo
            if((isset($settings['site_logo'])&&!(strpos($settings['site_logo'], '/') !== false))){
                $settings['site_logo']="/uploads/settings/".$settings['site_logo']."_logo.jpg";
            }

            //Search
            if((isset($settings['search'])&&!(strpos($settings['search'], '/') !== false))){
                $settings['search']="/uploads/settings/".$settings['search']."_cover.jpg";
            }

            //Details default cover image
            if((isset($settings['restorant_details_cover_image'])&&!(strpos($settings['restorant_details_cover_image'], '/') !== false))){
                $settings['restorant_details_cover_image']="/uploads/settings/".$settings['restorant_details_cover_image']."_cover.jpg";
            }

            //Restaurant default image
            if((isset($settings['restorant_details_image'])&&!(strpos($settings['restorant_details_image'], '/') !== false))){
                $settings['restorant_details_image']="/uploads/settings/".$settings['restorant_details_image']."_large.jpg";
            }

            config([
                'global' =>  $settings,
            ]);

        } catch (\Exception $e) {
            //return redirect()->route('LaravelInstaller::welcome');

        }


    }
}
