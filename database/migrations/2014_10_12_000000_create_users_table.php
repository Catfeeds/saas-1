<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->string('openid', 64)->nullable()->comment('微信openid');
            $table->char('company_guid', 32)->nullable()->comment('所属公司guid');
            $table->char('rel_guid')->nullable()->comment('公司组织架构关联guid');
            $table->string('name', 64)->nullable()->comment('用户姓名');
            $table->string('tel', 16)->nullable()->comment('用户电话');
            $table->string('password')->nullable()->comment('登录密码');
            $table->char('role_guid')->nullable()->comment('角色guid');
            $table->string('pic',32)->nullable()->comment('用户图像');
            $table->tinyInteger('status')->default(1)->comment('状态 1: 在职 2: 离职 3: 冻结');
            $table->string('remarks',32)->nullable()->comment('职位备注');
            $table->rememberToken();
            $table->timestamps();
        });
        \DB::statement("alter table `users` comment'用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
