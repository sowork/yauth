<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYAuthItemsChildsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 授权条目层次关系表
        Schema::create('yauth_items_childs', function (Blueprint $table){
            $table->engine = 'InnoDB';
            $table->unsignedInteger('parent_item_id');
            $table->unsignedInteger('child_item_id')->index();
            $table->softDeletes();

            $table->primary(['parent_item_id', 'child_item_id']);
            $table->foreign('parent_item_id')->references('id')->on('yauth_items');
            $table->foreign('child_item_id')->references('id')->on('yauth_items');
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
        Schema::dropIfExists('yauth_items_childs');
    }
}
