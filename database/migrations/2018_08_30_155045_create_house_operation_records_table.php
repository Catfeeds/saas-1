<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseOperationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_operation_records', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('house_guid',32)->nullable()->comment('房源guid');
            $table->char('visit_guid',32)->nullable()->comment('带看guid');
            $table->char('track_guid',32)->nullable()->comment('跟进guid');
            $table->tinyInteger('type')->nullable()->comment('操作类型 1: 跟进 2: 带看 3: 图片 4: 查看 5: 价格 6: 其他');
            $table->char('user_guid',32)->nullable()->comment('操作人');
            $table->string('remarks',128)->nullable()->comment('操作');
            $table->json('img')->nullable()->comment('编辑的图片');
            $table->timestamps();
        });
        \DB::statement("alter table `house_operation_records` comment'房源操作记录'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_operation_records');
    }
}
