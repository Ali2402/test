<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Logs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->tinyInteger('type')->comment('1 = auth , 2 = transaction');
            $table->string('desc');
            $table->bigInteger('amount')->nullable();
            $table->string('ip_address');
            $table->string('user_agent');
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        Schema::drop('logs');
    }
}
