<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeadlineColumnToStoryAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<< HEAD
        Schema::table('story_assignments', function (Blueprint $table) {
=======
        Schema::table('user_story_assignments', function (Blueprint $table) {
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
            $table->dateTime('deadline')->nullable()->after('completed');
            $table->dateTime('completed_at')->nullable()->after('deadline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
<<<<<<< HEAD
        Schema::table('story_assignments', function (Blueprint $table) {
=======
        Schema::table('user_story_assignments', function (Blueprint $table) {
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
            $table->dropColumn('deadline');
            $table->dropColumn('completed_at');
        });
    }
}
