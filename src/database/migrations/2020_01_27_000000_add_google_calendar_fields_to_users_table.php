<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogleCalendarFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('credentials');
            $table->text('channel_unique_id')->nullable();
            $table->text('channel_resource_id')->nullable();
            $table->text('channel_expires_at')->nullable();
            $table->text('channel_resource_url')->nullable();
            $table->text('sync_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'credentials',
                'channel_unique_id',
                'channel_resource_id',
                'channel_expires_at',
                'channel_resource_url',
                'sync_token',
            ]);
        });
    }
}