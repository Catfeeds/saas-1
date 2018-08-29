<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminds', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->string('remind_info',255)->nullable()->comment('提醒信息');
            $table->char('user_guid',32)->nullable()->comment('提醒人');
            $table->string('model_type',32)->nullable()->comment('提醒model');
            $table->char('rel_guid',32)->nullable()->comment('提醒房源/客源');
            $table->datetime('remind_time')->nullable()->comment('提醒时间');
            $table->tinyInteger('status')->default(1)->comment('是否提醒 1: 未提醒 2: 已提醒');
            $table->timestamps();
        });
        \DB::statement("alter table `reminds` comment'提醒表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminds');
    }
}
