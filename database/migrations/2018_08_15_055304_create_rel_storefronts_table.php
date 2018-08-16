<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelStorefrontsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_storefronts', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('storefronts_guid',32)->nullable()->comment('门店guid');
            $table->char('rel_guid',32)->nullable()->comment('片区/组guid');
            $table->string('model_type',32)->nullable()->comment('片区/组model');
            $table->timestamps();
        });
        \DB::statement("alter table `rel_storefronts` comment'片区,组与门店关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_storefronts');
    }
}
