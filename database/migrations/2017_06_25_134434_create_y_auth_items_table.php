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
            $table->string('item_name')->unique(); // 节点名称
            $table->unsignedTinyInteger('item_type')->index();// 节点类型 1=角色 2=权限
            $table->string('item_desc'); // 描述
            $table->softDeletes(); // 软删除列

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
