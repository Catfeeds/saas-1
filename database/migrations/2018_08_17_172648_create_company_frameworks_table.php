<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyFrameworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_frameworks', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->string('name',32)->nullable()->comment('名称');
            $table->char('company_guid', 32)->nullable()->comment('所属公司guid');
            $table->char('parent_guid',32)->nullable()->comment('父级guid');
            $table->tinyInteger('level')->nullable()->comment('架构级别 1: 片区 2: 门店 3: 组');
            $table->timestamps();
        });
        \DB::statement("alter table `company_frameworks` comment'公司组织架构表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_frameworks');
    }
}
