<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUidColumnInStoryMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('story_matches', function (Blueprint $table) {
            if (!Schema::hasColumn('story_matches', 'uid')) {
                $table->uuid('uid')->index()->nullable()->after('id');
            }
        });
        Schema::table('story_match_results', function (Blueprint $table) {
            $table->string('match_answer_uid')->nullable()->after('story_result_id');
            $table->foreign('match_answer_uid')->references('uid')->on('story_matches')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('story_matches', function (Blueprint $table) {
            $table->dropColumn('uid');
        });
        Schema::table('story_match_results', function (Blueprint $table) {
            $table->dropForeign('story_match_result_match_answer_uid_foreign');
            $table->dropColumn('match_answer_uid');
        });
    }
}
