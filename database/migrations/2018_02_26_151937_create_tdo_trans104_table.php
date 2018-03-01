<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTdoTrans104Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tdo_trans104_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('field');
            $table->string('primary')->nullable();
            $table->integer('verify');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tdo_trans104_tables');
    }
}
