<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYAuthItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 授权条目表
        Schema::create('yauth_items', function(Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('item_code')->unique();
            $table->string('item_name');
            $table->string('item_desc');
            $table->unsignedTinyInteger('item_type');
            $table->string('scope');

            $table->softDeletes();
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
        //
        Schema::dropIfExists('yauth_items');
    }
}
