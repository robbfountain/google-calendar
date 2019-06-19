<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('credentials');
            $table->string('channel_unique_id')->nullable();
            $table->string('channel_resource_id')->nullable();
            $table->string('channel_expires_at')->nullable();
            $table->string('channel_resource_url')->nullable();
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
        Schema::dropIfExists('google_clients');
    }
}
