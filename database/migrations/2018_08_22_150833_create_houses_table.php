<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');

            // 主要信息
            $table->tinyInteger('house_type')->default(1)->comment('房源类型 1: 写字楼 2: 住宅 3: 别墅 4: 商铺 5: 厂房 6: 仓库 7: 车位 8: 土地');
            $table->tinyInteger('public_private')->default(1)->comment('公私盘 1: 私盘 2: 公盘');
            $table->json('owner_info')->nullable()->comment('业主联系方式: json');
            $table->string('name',32)->nullable()->comment('小区');
            $table->string('pedestal',16)->nullable()->comment('栋座');
            $table->tinyInteger('pedestal_unit')->nullable()->comment('栋座单位 1: 栋 2: 弄 3: 座 4: 号 5: 号楼 6: 胡同');
            $table->string('unit', 32)->nullable()->comment('单元');
            $table->tinyInteger('unit_unit')->nullable()->comment('单元单位 1: 单元 2: 栋 3: 幢 4: 号 5: 号楼');
            $table->string('house_number',64)->nullable()->comment('房号');

            // 基本信息
            $table->tinyInteger('grade')->nullable()->comment('房源等级 1: A类 2: B类 3: C类');
            $table->decimal('price',10,2)->nullable()->comment('租金');
            $table->tinyInteger('price_unit')->nullable()->comment('租金单位 1: 元/月 2: 元/平/月 3: 元/平/天');
            $table->tinyInteger('payment_type')->nullable()->comment('付款方式: 1: 押一付三 2: 押一付二 3: 押一付一 4: 押二付一 5: 押三付一 6: 半年付 7: 年付 8: 面谈');
            $table->string('increasing_situation_remark', 256)->nullable()->comment('递增情况');
            $table->json('cost_detail')->nullable()->comment('费用明细: json');
            $table->string('acreage',32)->nullable()->comment('面积(平)');
            $table->tinyInteger('split')->nullable()->comment('可拆分 1: 可拆分 2: 不可拆分');
            $table->string('mini_acreage',32)->nullable()->comment('最小面积(平)');
            $table->integer('floor')->nullable()->comment('楼层(层)');
            $table->integer('total_floor')->nullable()->comment('总楼层(层)');
            $table->float('floor_height',10,2)->nullable()->comment('层高(米)');
            $table->tinyInteger('property_grade')->nullable()->comment('物业评级 1: 甲级 2: 乙级 3: 丙级 4 :其他');
            $table->decimal('property_fee',10,2)->nullable()->comment('物业费(平/元)');
            $table->tinyInteger('register_company')->nullable()->comment('注册公司 1: 可以 2: 不可以');
            $table->tinyInteger('type')->nullable()->comment('写字楼类型 1: 纯写字楼 2: 商住楼 3: 商业综合体楼 4: 酒店写字楼 5: 其他');
            $table->tinyInteger('orientation')->nullable()->comment('朝向: 1: 东 2: 西 3: 南 4: 北 5: 东南 6: 东北 7: 西南 8: 西北 9: 东西 10: 南北');
            $table->tinyInteger('renovation')->nullable()->comment('装修: 1: 豪华装修 2: 精装修 3: 中装修 4: 间装修 5: 毛坯');
            $table->tinyInteger('open_bill')->nullable()->comment('可开发票 1: 可以 2: 不可以');
            $table->string('station_number',32)->nullable()->comment('工位数量');
            $table->integer('rent_free')->nullable()->comment('免租期');
            $table->json('support_facilities')->nullable()->comment('配套设施');

            // 更多信息
            $table->tinyInteger('source')->nullable()->comment('来源渠道: 1: 上门 2: 电话 3: 洗盘 4: 网络 5: 陌拜 6: 转介绍 7: 老客户');
            $table->tinyInteger('status')->nullable()->comment('现状 1: 空置 2: 自用 3: 在租');
            $table->tinyInteger('shortest_lease')->nullable()->comment('最短租期: 1: 1年 2: 2年 3: 3年 4: 5年 5: 5年以上');
            $table->text('remarks')->nullable()->comment('备注');

            $table->timestamps();
        });
        \DB::statement("alter table `houses` comment'房源表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('houses');
    }
}
