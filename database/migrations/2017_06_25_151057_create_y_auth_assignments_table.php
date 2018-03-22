<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYAuthAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yauth_assignments', function (Blueprint $table){
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->unsignedInteger('relation_id');
            $table->unsignedInteger('user_id');
            $table->string('provider');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('relation_id')->references('id')->on('yauth_item_relation');
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
        Schema::dropIfExists('yauth_assignments');
    }
}
