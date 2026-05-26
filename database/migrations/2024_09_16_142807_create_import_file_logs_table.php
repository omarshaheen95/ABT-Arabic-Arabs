<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportFileLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_file_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_file_id');
            $table->integer('row_num')->nullable();
            $table->text('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('import_file_id')->references('id')->on('import_files')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_file_logs');
    }
}
