<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUidColumnInMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->uuid('uid')->index()->nullable()->after('id');
        });
        Schema::table('match_results', function (Blueprint $table) {
            $table->string('match_answer_uid')->nullable()->after('result_id');
            $table->foreign('match_answer_uid')->references('uid')->on('matches')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('match', function (Blueprint $table) {
            $table->dropColumn('uid');
        });
        Schema::table('match_results', function (Blueprint $table) {
            $table->dropForeign('match_results_match_answer_uid_foreign');
            $table->dropColumn('match_answer_uid');
        });
    }
}
