<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examples', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('fields2');
            $table->string('fields3');
            $table->integer('fields4')->nullable();
            $table->boolean('fields5');
            $table->boolean('fields6');
            $table->text('fields7');
            $table->text('fields8');
            $table->date('fields9')->nullable();
            $table->integer('order');
            $table->boolean('is_crop');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examples');
    }
}
