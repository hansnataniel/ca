<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryalbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleryalbums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('gallerycategory_id');
            $table->text('description');
            $table->integer('order');
            $table->boolean('is_crop');
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
        Schema::dropIfExists('galleryalbums');
    }
}
