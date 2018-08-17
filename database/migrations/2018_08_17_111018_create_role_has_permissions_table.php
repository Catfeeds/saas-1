<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('role_guid', 32)->nullable()->comment('角色guid');
            $table->char('permission_guid', 32)->nullable()->comment('权限guid');
            $table->tinyInteger('action_scope')->nullable()->comment('作用域 1: 全公司 2: 本区 3: 本店 4: 本组 5: 本人 6: 无');
            $table->tinyInteger('operation_number')->nullable()->comment('操作数量');
            $table->tinyInteger('follow_up')->nullable()->comment('是否跟进 1: 查看前 2: 查看后 3: 不需要');
            $table->timestamps();
        });
        \DB::statement("alter table `role_has_permissions` comment'角色权限关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_has_permissions');
    }
}
