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
            $table->increments('ichild_id');
            $table->string('item_name'); // items 名称
            $table->unsignedInteger('parent_id'); // 父节点ID
            $table->unsignedInteger('lft'); // 左值
            $table->unsignedInteger('rgt'); // 右值
            $table->unsignedInteger('depth'); // 深度
            $table->softDeletes();

            $table->foreign('item_name')->references('item_name')->on('yauth_items');
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