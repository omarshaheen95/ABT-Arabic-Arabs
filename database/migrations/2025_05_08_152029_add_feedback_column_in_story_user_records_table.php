<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeedbackColumnInStoryUserRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('story_user_records', function (Blueprint $table) {
            $table->string('feedback_message',5000)->nullable()->after('record');
            $table->string('feedback_audio_message')->nullable()->after('feedback_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('story_user_records', function (Blueprint $table) {
             $table->dropColumn(['feedback_message','feedback_audio_message']);
        });
    }
}
