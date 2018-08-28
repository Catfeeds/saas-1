<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('user_guid',32)->nullable()->comment('跟进人guid');
            $table->string('model_type',32)->nullable()->comment('model');
            $table->char('rel_guid',32)->nullable()->comment('房源/客源guid');
            $table->string('tracks_info',255)->nullable()->comment('跟进信息');
            $table->timestamps();
        });
        \DB::statement("alter table `tracks` comment'跟进表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracks');
    }
}
