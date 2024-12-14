<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoryAssignmentIdInUserStoryAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_story_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('story_assignment_id')->after('story_id')->nullable();
            $table->foreign('story_assignment_id')->on('story_assignments')->references('id')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_story_assignments', function (Blueprint $table) {
            $table->dropColumn('story_assignment_id');
        });
    }
}
