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
            $table->char('company_guid', 32)->nullable()->comment('公司guid');
            // 主要信息
            $table->tinyInteger('house_type')->default(1)->comment('房源类型 1: 写字楼 2: 住宅 3: 别墅 4: 商铺 5: 厂房 6: 仓库 7: 车位 8: 土地');

            // 核心信息
            $table->json('owner_info')->nullable()->comment('业主联系方式: json');
            $table->char('building_block_guid',32)->nullable()->comment('楼座guid');
            $table->integer('floor')->nullable()->comment('楼层(层)');
            $table->string('house_number',64)->nullable()->comment('房号');

            // 基础信息
            $table->tinyInteger('grade')->nullable()->comment('房源等级 1: A类 2: B类 3: C类');
            $table->tinyInteger('public_private')->default(1)->comment('公私盘 1: 私盘 2: 公盘');
            $table->decimal('price',10,2)->nullable()->comment('租金');
            $table->tinyInteger('price_unit')->nullable()->comment('租金单位 1: 元/月 2: 元/平/月 3: 元/平/天');
            $table->tinyInteger('payment_type')->nullable()->comment('付款方式: 1: 押一付三 2: 押一付二 3: 押一付一 4: 押二付一 5: 押三付一 6: 半年付 7: 年付 8: 面谈');
            $table->string('increasing_situation_remark', 256)->nullable()->comment('递增情况');
            $table->json('cost_detail')->nullable()->comment('费用明细: 物业费；水费；电费；宽带费；取暖费；停车费');
            $table->string('acreage',32)->nullable()->comment('面积(平)');
            $table->tinyInteger('split')->nullable()->comment('可拆分 1: 可拆分 2: 不可拆分');
            $table->string('mini_acreage',32)->nullable()->comment('最小面积(平)');
            $table->integer('total_floor')->nullable()->comment('总楼层(层)');
            $table->float('floor_height',10,2)->nullable()->comment('层高(米)');
            $table->tinyInteger('register_company')->nullable()->comment('注册公司 1: 可以 2: 不可以');
            $table->tinyInteger('type')->nullable()->comment('写字楼类型 1: 纯写字楼 2: 商住楼 3: 商业综合体楼 4: 酒店写字楼 5: 其他');
            $table->tinyInteger('orientation')->nullable()->comment('朝向: 1: 东 2: 南 3: 西 4: 北 5: 东南 6: 东北 7: 西南 8: 西北 9: 南北 10: 东西');
            $table->tinyInteger('renovation')->nullable()->comment('装修: 1: 豪华装修 2: 精装修 3: 中装修 4: 间装修 5: 毛坯');
            $table->tinyInteger('open_bill')->nullable()->comment('可开发票 1: 可以 2: 不可以');
            $table->string('station_number',32)->nullable()->comment('工位数量');
            $table->integer('rent_free')->nullable()->comment('免租期');
            $table->json('support_facilities')->nullable()->comment('配套设施: 空调；办公家具；沙发；宽带；冰箱');

            // 更多信息
            $table->tinyInteger('source')->nullable()->comment('来源渠道: 1: 上门 2: 电话 3: 洗盘 4: 网络 5: 陌拜 6: 转介绍 7: 老客户');
            $table->tinyInteger('actuality')->nullable()->comment('现状 1: 空置 2: 自用 3: 在租');
            $table->tinyInteger('shortest_lease')->nullable()->comment('最短租期: 1: 1年 2: 2年 3: 3年 4: 5年 5: 5年以上');
            $table->text('remarks')->nullable()->comment('备注');

            // 图片
            $table->json('house_type_img')->nullable()->comment('户型图:json  图片,介绍');
            $table->json('indoor_img')->nullable()->comment('室内图:json   图片,介绍');
            $table->json('outdoor_img')->nullable()->comment('室外图:json  图片,介绍');

            // 操作人
            $table->char('entry_person',32)->nullable()->comment('录入人');
            $table->char('guardian_person',32)->nullable()->comment('维护人');
            $table->char('pic_person',32)->nullable()->comment('图片人');
            $table->char('key_person',32)->nullable()->comment('钥匙人');

            // 有无钥匙
            $table->tinyInteger('have_key')->default(2)->comment('有无钥匙 1: 有 2: 没有');
            // 房源状态
            $table->tinyInteger('status')->default(1)->comment('房源状态 1: 有效 2: 无效 3: 无效-暂缓 4: 无效-内成交 5: 无效-外成交 6: 无效-信息有误 7: 无效-其他');
            //  置顶
            $table->tinyInteger('top')->default(2)->comment('是否置顶 1: 置顶 2: 不置顶');
            // 跟进时间
            $table->datetime('track_time')->nullable()->comment('跟进时间');

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
