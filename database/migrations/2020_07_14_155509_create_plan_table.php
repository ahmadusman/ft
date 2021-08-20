<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            
            $table->integer('limit_items')->default(0)->comment('0 is unlimited');
            $table->integer('limit_orders')->default(0)->comment('0 is unlimited');

            $table->float('price');
            $table->integer('period')->default(1)->comment('1 - monthly, 2-anually');

            $table->string('paddle_id');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan');
    }
}
