<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseShareRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_share_records', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('house_guid',32)->nullable()->comment('房源guid');
            $table->string('remarks',32)->nullable()->comment('备注');
            $table->timestamps();
        });
        \DB::statement("alter table `house_share_records` comment'房源共享记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_share_records');
    }
}
