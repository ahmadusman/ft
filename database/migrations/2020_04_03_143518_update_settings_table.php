<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('typeform')->default("");
            $table->string('mobile_info_title')->default("");
            $table->string('mobile_info_subtitle')->default("");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('typeform');
            $table->dropColumn('mobile_info_title');
            $table->dropColumn('mobile_info_subtitle');
        });
    }
}
