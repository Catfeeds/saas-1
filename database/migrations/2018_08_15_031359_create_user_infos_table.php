<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('user_guid', 32)->nullable()->comment('用户guid');
            $table->tinyInteger('sex')->nullable()->comment('性别,1:男 2:女');
            $table->date('entry')->nullable()->comment('入职日期');
            $table->date('birth')->nullable()->comment('生日');
            $table->string('native_place',16)->nullable()->comment('籍贯');
            $table->string('race',16)->nullable()->comment('名族');
            $table->timestamps();
        });
        DB::statement("alter table `user_infos` comment'用户基础信息表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_infos');
    }
}
