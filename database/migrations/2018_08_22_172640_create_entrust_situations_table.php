<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntrustSituationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrust_situations', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('house_guid', 32)->nullable()->comment('房源guid');

            $table->tinyInteger('type')->nullable()->comment('委托书类型 1: 普通委托 2: 速销 3: 独家');
            $table->string('number')->nullable()->comment('委托书编号');
            $table->string('check_number')->nullable()->comment('核验编号');
            $table->json('entrust_time')->nullable()->comment('委托期限');
            $table->string('remarks',128)->nullable()->comment('备注');
            $table->json('entrust_single')->nullable()->comment('委托单: json');

            $table->timestamps();
        });
        \DB::statement("alter table `entrust_situations` comment'委托情况'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entrust_situations');
    }
}
