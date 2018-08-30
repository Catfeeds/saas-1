<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeeHouseWaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('see_house_ways', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('house_guid',32)->nullable()->comment('房源guid');
            $table->tinyInteger('type')->nullable()->comment('看房方式 1: 预约 2: 直接看 3: 借钥匙');
            $table->string('remarks',128)->nullable()->comment('备注');

            $table->char('storefront_guid')->nullable()->comment('门店guid');
            $table->string('received_remarks',128)->nullable()->comment('收匙备注');
            $table->string('key_single')->nullable()->comment('钥匙单');
            $table->string('key_number')->nullable()->comment('钥匙编号');
            $table->dateTime('received_time')->nullable()->comment('收匙日期');

            $table->timestamps();
        });
        \DB::statement("alter table `see_house_ways` comment'看房方式'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('see_house_ways');
    }
}
