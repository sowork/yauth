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
            $table->string('item_name'); // 关联权限
            $table->unsignedInteger('user_id'); // 用户ID
            $table->string('guard'); // 用户的guard名称
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('item_name')->references('item_name')->on('yauth_items');
            $table->primary(['item_name', 'user_id']);
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
