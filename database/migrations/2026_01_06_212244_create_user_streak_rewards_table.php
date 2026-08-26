<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStreakRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_streak_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['weekly', 'monthly'])->comment('Type of streak reward');
            $table->integer('streak_days')->comment('Number of consecutive days when reward was given');
            $table->integer('points_awarded')->comment('XP points awarded');
            $table->timestamp('awarded_at');
            $table->timestamps();

            $table->index(['user_id', 'type', 'awarded_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_streak_rewards');
    }
}
