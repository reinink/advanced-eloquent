<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginsTable extends Migration
{
    public function up()
    {
        Schema::create('logins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->timestamp('created_at');
        });
    }
}
