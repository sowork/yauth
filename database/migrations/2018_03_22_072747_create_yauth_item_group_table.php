<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYauthItemGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yauth_item_group', function (Blueprint $table) {
            $table->engine = 'innoDb';
            $table->increments('id');
            $table->unsignedInteger('parent_id')->index();
            $table->unsignedInteger('child_id')->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('yauth_items');
            $table->foreign('child_id')->references('id')->on('yauth_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yauth_item_group');
    }
}
