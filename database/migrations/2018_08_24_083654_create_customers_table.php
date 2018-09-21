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
            $table->char('company_guid',32)->nullable()->comment('公司guid');
            $table->tinyInteger('level')->nullable()->comment('客源等级 1: A 2: B 3: C');
            $table->tinyInteger('guest')->nullable()->comment('客别 1: 公客 2: 私客');
            $table->json('customer_info')->nullable()->comment('客源信息: json');
            $table->string('remarks',512)->nullable()->comment('备注');
            $table->json('intention')->nullable()->comment('需求意向: json(区域)');
            $table->json('block')->nullable()->comment('商圈: json(商圈)');
            $table->json('building')->nullable()->comment('楼盘: json(楼盘)');
            $table->json('house_type')->nullable()->comment('户型: json');
            $table->decimal('min_price',10)->nullable()->comment('最低价/元/㎡.月');
            $table->decimal('max_price',10)->nullable()->comment('最高价格/元/㎡.月');
            $table->decimal('min_acreage',10)->nullable()->comment('最低面积/㎡');
            $table->decimal('max_acreage',10)->nullable()->comment('最高面积/㎡');
            $table->tinyInteger('type')->nullable()->comment('类型(待定) 1: 纯写字楼 2: 商住楼 3: 商业综合体 4: 酒店写字楼 5: 其它');
            $table->tinyInteger('renovation')->nullable()->comment('装修: 1: 豪华装修 2: 精装修 3: 中装修 4: 间装修 5: 毛坯');
            $table->string('min_floor',32)->nullable()->comment('最低楼层/层');
            $table->string('max_floor',32)->nullable()->comment('最高楼层/层');
            $table->tinyInteger('status')->nullable()->comment('状态 1: 正常 2: 无效 3: 暂缓 4: 内成交 5: 外成交 6: 电话错误 7: 其他');
            $table->string('invalid_reason',256)->nullable()->comment('无效原因');
            $table->char('entry_person',32)->nullable()->comment('录入人');
            $table->char('guardian_person',32)->nullable()->comment('维护人');
            // 跟进时间
            $table->datetime('track_time')->nullable()->comment('跟进时间');
            $table->timestamps();
            $table->softDeletes();
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
