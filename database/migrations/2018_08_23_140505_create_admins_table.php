<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->string('name', 32)->nullable()->comment('用户姓名');
            $table->string('password',64)->nullable()->comment('登录密码');
            $table->string('nike_name',32)->nullable()->comment('昵称');
            $table->timestamps();
        });
        \DB::statement("alter table `admins` comment'总后台登录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
