<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddReportIndexesToUsageTables extends Migration
{
    /**
     * Indexes the usage / teacher reports rely on. Several of these tables were
     * created without an index on their foreign keys, which forced a full table
     * scan for every student the reports looked at.
     */
    private $indexes = [
        'user_tests' => [
            'user_tests_user_id_index' => ['user_id'],
            'user_tests_lesson_id_index' => ['lesson_id'],
            'user_tests_created_at_index' => ['created_at'],
            'user_tests_user_id_status_index' => ['user_id', 'status'],
        ],
        'story_user_records' => [
            'story_user_records_user_id_index' => ['user_id'],
            'story_user_records_story_id_index' => ['story_id'],
            'story_user_records_created_at_index' => ['created_at'],
        ],
        'student_story_tests' => [
            'student_story_tests_created_at_index' => ['created_at'],
            'student_story_tests_user_id_status_index' => ['user_id', 'status'],
        ],
        'user_trackers' => [
            'user_trackers_created_at_index' => ['created_at'],
        ],
        'user_lessons' => [
            'user_lessons_created_at_index' => ['created_at'],
        ],
        'user_assignments' => [
            'user_assignments_user_id_index' => ['user_id'],
            'user_assignments_created_at_index' => ['created_at'],
        ],
        'user_story_assignments' => [
            'user_story_assignments_user_id_index' => ['user_id'],
            'user_story_assignments_created_at_index' => ['created_at'],
        ],
        'stories' => [
            'stories_grade_index' => ['grade'],
        ],
        'teacher_users' => [
            'teacher_users_teacher_id_user_id_index' => ['teacher_id', 'user_id'],
        ],
    ];

    public function up()
    {
        foreach ($this->indexes as $table => $indexes) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($indexes as $name => $columns) {
                if (!$this->columnsExist($table, $columns) || $this->indexExists($table, $name)) {
                    continue;
                }

                $columns = implode('`, `', $columns);
                DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$name}` (`{$columns}`)");
            }
        }
    }

    public function down()
    {
        foreach ($this->indexes as $table => $indexes) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach (array_keys($indexes) as $name) {
                if ($this->indexExists($table, $name)) {
                    DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$name}`");
                }
            }
        }
    }

    private function indexExists($table, $name)
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$name])) > 0;
    }

    private function columnsExist($table, array $columns)
    {
        foreach ($columns as $column) {
            if (!Schema::hasColumn($table, $column)) {
                return false;
            }
        }

        return true;
    }
}
