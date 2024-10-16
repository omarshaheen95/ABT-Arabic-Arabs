<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageColumnInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervisors', function (Blueprint $table) {
            $table->string('image')->after('email')->nullable();
        });
         Schema::table('teachers', function (Blueprint $table) {
            $table->string('image')->after('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
