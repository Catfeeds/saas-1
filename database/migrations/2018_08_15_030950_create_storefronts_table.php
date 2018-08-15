<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorefrontsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storefronts', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('主键guid');
            $table->char('area_guid', 32)->nullable()->comment('片区guid');
            $table->string('name', 32)->nullable()->comment('门店名称');
            $table->timestamps();
        });
        DB::statement("alter table `storefronts` comment'门店表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storefronts');
    }
}
