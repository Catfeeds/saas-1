<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->string('name', 32)->nullabel()->comment('片区名称');
            $table->char('company_guid', 32)->nullable()->comment('所属公司id');
            $table->timestamps();
        });
        DB::statement("alter table `areas` comment'片区表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
