<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUidColumnInSortWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('sort_words', function (Blueprint $table) {
            $table->uuid('uid')->index()->nullable()->after('id');
        });
        Schema::table('sort_results', function (Blueprint $table) {
            $table->string('sort_answer_uid')->nullable()->after('sort_word_id');
            $table->foreign('sort_answer_uid')->references('uid')->on('sort_words')->cascadeOnDelete();
        });
        Schema::table('story_sort_words', function (Blueprint $table) {
            $table->uuid('uid')->index()->nullable()->after('id');
        });
        Schema::table('story_sort_results', function (Blueprint $table) {
            $table->string('story_sort_answer_uid')->nullable()->after('story_sort_word_id');
            $table->foreign('story_sort_answer_uid')->references('uid')->on('story_sort_words')->cascadeOnDelete();
        });

    }

    public function down(): void
    {
        Schema::table('sort_words', function (Blueprint $table) {
            $table->dropColumn('uid');
        });
        Schema::table('sort_results', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sort_answer_uid');
        });
        Schema::table('story_sort_words', function (Blueprint $table) {
            $table->dropColumn('uid');
        });
        Schema::table('story_sort_results', function (Blueprint $table) {
            $table->dropConstrainedForeignId('story_sort_answer_uid');
        });
    }
}
