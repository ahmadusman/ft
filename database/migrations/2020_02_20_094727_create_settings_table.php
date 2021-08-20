<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('site_name');
            $table->string('site_logo');
            $table->string('search');
            $table->string('restorant_details_image')->default("");
            $table->string('restorant_details_cover_image')->default("");
            $table->string('description');
            $table->string('header_title');
            $table->string('header_subtitle');
            $table->string('currency')->default("USD");
            $table->string('facebook')->default("");
            $table->string('instagram')->default("");
            $table->string('playstore')->default(env('PLAYSTORE',""));
            $table->string('appstore')->default(env('APPSTORE',""));
            $table->string('maps_api_key')->default("")->nullable();
            $table->float('delivery')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
