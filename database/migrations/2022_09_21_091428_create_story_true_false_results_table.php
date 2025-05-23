<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryTrueFalseResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_true_false_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_story_test_id')->nullable();
            $table->unsignedBigInteger('story_question_id');
            $table->boolean('result');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_story_test_id')->references('id')->on('student_story_tests')->cascadeOnDelete();
            $table->foreign('story_question_id')->references('id')->on('story_questions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_true_false_results');
    }
}
