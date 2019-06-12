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
                $table->text('access_token');
                $table->text('refresh_token');
                $table->string('scope');
                $table->string('token_type');
                $table->dateTime('created');
                $table->integer('expires_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     Schema::table('users',function(Blueprint $table) {
         $table->dropColumn([
             'access_token',
             'refresh_token',
             'scope',
             'token_type',
             'created',
             'expires_in',
         ]);
     });
    }
}
