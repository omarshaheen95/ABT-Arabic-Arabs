<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('added_by_id')->nullable()->after('archived');
            $table->string('added_by_type')->nullable()->after('added_by_id');
            $table->string('direct_email')->nullable()->after('added_by_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('added_by_id');
            $table->dropColumn('added_by_type');
            $table->dropColumn('direct_email');
        });
    }
};
