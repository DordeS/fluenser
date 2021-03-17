<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfluencersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('influencers_info', function (Blueprint $table) {
            $table->id();
            $table->integer('influencer_id');
            $table->string('country')->default('unknown');
            $table->string('state')->default('unknown');
            $table->integer('follows')->default(0);
            $table->integer('followings')->default(0);
            $table->integer('posts')->default(0);
            $table->string('avatar')->default('johndoeavatar');
            $table->string('back_img')->default('johndoeback');
            $table->float('arg_rate')->default(0.0);
            $table->integer('bf_rate')->default(0);
            $table->integer('tm_rate')->default(0);
            $table->integer('m_rate')->default(0);
            $table->integer('reviews')->default(0);
            $table->float('rating')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('influencers_info');
    }
}