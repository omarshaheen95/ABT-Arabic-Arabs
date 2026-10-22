<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The columns spatie/laravel-medialibrary 8 to 11 added to the media table of
 * version 7: uuid, conversions_disk and generated_conversions.
 *
 * Version 7 kept the generated conversions in custom_properties; version 11
 * reads only its own column. They are copied there, and left in
 * custom_properties as well, so the Laravel 8 code still works on this table
 * if the deploy is rolled back. For the same reason the new columns are
 * nullable: version 7 does not fill them when it adds a file. Version 11 reads
 * a null conversions_disk as the media's disk and a null
 * generated_conversions as none, and fills all three for the files it adds.
 * The existing files keep a null uuid (only medialibrary-pro uses it).
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            if (! Schema::hasColumn('media', 'uuid')) {
                $table->uuid('uuid')->nullable()->unique();
            }
            if (! Schema::hasColumn('media', 'conversions_disk')) {
                $table->string('conversions_disk')->nullable();
            }
            if (! Schema::hasColumn('media', 'generated_conversions')) {
                $table->json('generated_conversions')->nullable();
            }
        });

        DB::table('media')->whereNull('conversions_disk')->update(['conversions_disk' => DB::raw('disk')]);
        DB::table('media')->whereNull('generated_conversions')->update([
            'generated_conversions' => DB::raw("coalesce(json_extract(custom_properties, '$.generated_conversions'), json_object())"),
        ]);
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            if (Schema::hasColumn('media', 'uuid')) {
                $table->dropUnique(['uuid']);
            }
        });
        Schema::table('media', function (Blueprint $table) {
            foreach (['uuid', 'conversions_disk', 'generated_conversions'] as $column) {
                if (Schema::hasColumn('media', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
