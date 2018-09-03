<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOperationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_operation_records', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('customer_guid',32)->nullable()->comment('客源guid');
            $table->tinyInteger('type')->nullable()->comment('操作类型 1: 跟进 2: 带看 3: 查看 4: 其他');
            $table->char('user_guid',32)->nullable()->comment('操作人');
            $table->string('remarks',128)->nullable()->comment('操作备注');
            $table->timestamps();
        });
        \DB::statement("alter table `customer_operation_records` comment'客源操作记录'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_operation_records');
    }
}
