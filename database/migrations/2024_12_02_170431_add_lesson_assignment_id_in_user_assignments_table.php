<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLessonAssignmentIdInUserAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_assignment_id')->after('lesson_id')->nullable();
            $table->foreign('lesson_assignment_id')->on('lesson_assignments')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_assignments', function (Blueprint $table) {
            $table->dropColumn('lesson_assignment_id');
        });
    }
}
