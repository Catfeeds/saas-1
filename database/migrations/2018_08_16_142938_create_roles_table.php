<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('company_guid',32)->nullable()->comment('公司guid');
            $table->string('name',32)->nullable()->comment('角色名');
            $table->tinyInteger('level')->nullable()->comment('角色等级 1: 公司 2: 片区 3: 门店 4: 分组 5: 个人');
            $table->timestamps();
        });
        DB::statement("alter table `roles` comment'角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
