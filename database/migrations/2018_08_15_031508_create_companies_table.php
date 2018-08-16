<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->string('name',128)->nullable()->comment('公司名称');
            $table->string('slogan',512)->nullable()->comment('公司口号');
            $table->string('license',512)->nullable()->comment('营业执照');
            $table->string('address',256)->nullable()->comment('公司地址');
            $table->timestamps();
        });
        \DB::statement("alter table `companies` comment'公司表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
