<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsergroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usergroups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->boolean('profile_r');
            $table->boolean('profile_u');

            $table->boolean('gallery_r');
            $table->boolean('gallery_u');

            $table->boolean('is_active');

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
        Schema::dropIfExists('usergroups');
    }
}
