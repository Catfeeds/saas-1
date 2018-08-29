<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('visit_user',32)->nullable()->comment('带看人');
            $table->char('accompany',32)->nullable()->comment('陪看人');
            $table->char('rel_guid',32)->nullable()->comment('带看的客源/房源');
            $table->string('remarks',255)->nullable()->comment('带看备注');
            $table->string('visit_img',32)->nullable()->comment('带看单');
            $table->dateTime('visit_date')->nullable()->comment('带看日期');
            $table->tinyInteger('visit_time')->nullable()->comment('带看时间 1: 上午 2: 下午 3: 晚上');
            $table->timestamps();
        });
        \DB::statement("alter table `visits` comment'带看表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
    }
}
