<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_backs', function (Blueprint $table) {
            $table->char('guid',32)->primary()->comment('guid主键');
            $table->char('user_guid',32)->nullable()->comment('登录用户guid');
            $table->string('content',255)->nullable()->comment('反馈内容');
            $table->timestamps();
        });
        \DB::statement("alter table `feed_backs` comment'反馈信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_backs');
    }
}
