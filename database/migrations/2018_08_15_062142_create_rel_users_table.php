<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_users', function (Blueprint $table) {
            $table->char('guid', 32)->primary()->comment('guid主键');
            $table->char('user_guid',32)->nullable()->comment('成员guid');
            $table->char('rel_guid',32)->nullable()->comment('门店/组guid');
            $table->string('model_type',32)->nullable()->comment('门店/组model');
            $table->timestamps();
        });
        DB::statement("alter table `rel_users` comment'门店,组与成员关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rel_users');
    }
}
