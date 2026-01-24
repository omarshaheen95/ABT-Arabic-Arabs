<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPointsColumnInTrackersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_trackers', function (Blueprint $table) {
            $table->integer('points')->after('type')->default(0);
        });
        Schema::table('user_tracker_stories', function (Blueprint $table) {
            $table->integer('points')->after('type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trackers_tables', function (Blueprint $table) {
            Schema::table('user_trackers', function (Blueprint $table) {
                $table->dropColumn('points');
            });
            Schema::table('user_tracker_stories', function (Blueprint $table) {
                $table->dropColumn('points');
            });
        });
    }
}
