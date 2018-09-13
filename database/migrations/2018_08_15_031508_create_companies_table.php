<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->string('name',128)->nullable()->comment('公司名称');
            $table->char('city_guid',32)->nullable()->comment('公司所在城市guid');
            $table->char('area_guid',32)->nullable()->comment('公司所在区域guid');
            $table->string('address',256)->nullable()->comment('公司地址');
            $table->string('company_tel',16)->nullable()->comment('公司电话');
            $table->tinyInteger('status')->nullable()->comment('账户启用状态 1: 启用 2: 禁用');
            $table->timestamps();
        });
        \DB::statement("alter table `companies` comment'公司表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
