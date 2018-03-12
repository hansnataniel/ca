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
            $table->increments('id');
            $table->integer('back_session_lifetime');
            $table->integer('front_session_lifetime');
            $table->integer('visitor_lifetime');
            $table->text('admin_url');

            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            $table->string('contact_email')->nullable();
            $table->string('receiver_email')->nullable();
            $table->string('receiver_email_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_email_name')->nullable();
            
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            
            $table->text('google_analytics')->nullable();
            $table->boolean('maintenance');
            
            $table->text('about');
            $table->string('coor');

            $table->integer('settingupdate_id');
            $table->integer('aboutupdate_id');
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
        Schema::dropIfExists('settings');
    }
}
