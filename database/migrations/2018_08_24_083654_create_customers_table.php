<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('company_guid',32)->nullable()->comment('所属公司');
            $table->tinyInteger('level')->nullable()->comment('客源等级 1: A 2: B 3: C');
            $table->tinyInteger('guest')->nullable()->comment('客别 1: 公客 2: 私客 3: 抢客');
            $table->json('customer_info')->nullable()->comment('客源信息: json');
            $table->string('remarks',512)->nullable()->comment('备注');
            $table->json('intention')->nullable()->comment('需求意向: json');
            $table->json('block')->nullable()->comment('商圈: json');
            $table->json('building')->nullable()->comment('楼盘: json');
            $table->json('house_type')->nullable()->comment('户型: json');
            $table->json('price')->nullable()->comment('总价(最低-最高): json');
            $table->json('acreage')->nullable()->comment('面积(最低-最高): json');
            $table->tinyInteger('type')->nullable()->comment('类型(待定) 1: 写字楼');
            $table->tinyInteger('renovation')->nullable()->comment('装修: 1: 豪华装修 2: 精装修 3: 中装修 4: 间装修 5: 毛坯');
            $table->json('floor')->nullable()->comment('楼层(最低-最高): json');
            $table->string('target',32)->nullable()->comment('目标');
            $table->timestamps();
        });
        \DB::statement("alter table `customers` comment'客源表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
