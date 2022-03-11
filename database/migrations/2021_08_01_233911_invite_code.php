<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InviteCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->tinyInteger('status')->comment('0 = invalid , 1= valid');
            $table->text('description');
            $table->string('author');
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
        Schema::drop('invite_code');
    }
}
