<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('story_assignments', function (Blueprint $table) {
            $table->foreignId('year_id')->nullable()->after('teacher_id')->constrained('years')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('story_assignments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('year_id');
        });
    }
};
