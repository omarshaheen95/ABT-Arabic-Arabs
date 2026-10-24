<?php

use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

require base_path('routes/general.php');

Route::group([], function() {

    Route::get('/home', [\App\Http\Controllers\Teacher\SettingController::class, 'home'])->name('home');
    //Set Local
    Route::get('lang/{local}', [\App\Http\Controllers\Teacher\SettingController::class, 'lang'])->name('switch-language');

    //Profile
    Route::get('profile/edit', [\App\Http\Controllers\Teacher\SettingController::class, 'editProfile'])->name('edit-profile');
    Route::post('profile/update', [\App\Http\Controllers\Teacher\SettingController::class, 'updateProfile'])->name('update-profile');
    Route::get('password/edit', [\App\Http\Controllers\Teacher\SettingController::class, 'editPassword'])->name('edit-password');
    Route::post('password/update', [\App\Http\Controllers\Teacher\SettingController::class, 'updatePassword'])->middleware('throttle:10,1')->name('update-password');


    //Curriculum
    Route::get('curriculum/{grade}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'curriculum'])->name('curriculum.home');
    Route::get('levels/{grade}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'lessonsLevels'])->name('levels');
    Route::get('stories/{grade}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'storiesLevels'])->name('levels.stories');

    Route::get('stories_level/{level}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'stories'])->name('stories.list');
    Route::get('stories/{id}/{key}/{grade}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'story'])->name('stories.show');

    Route::get('lessons/{id}/{type}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'lessons'])->name('lessons');
    Route::get('lessons_levels/{grade}/{type}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'subLevels'])->name('lessons_levels');
    Route::get('lesson/{id}/{key}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'lesson'])->name('lesson');
    Route::get('sub_lessons/{id}/{type}/{level}', [\App\Http\Controllers\Teacher\CurriculumController::class, 'subLessons'])->name('sub_lessons');

    //Reports
    Route::get('usage_report', [\App\Http\Controllers\Teacher\SettingController::class, 'usageReport'])->name('report.teacher_usage_report');
    Route::get('pre_usage_report', [\App\Http\Controllers\Teacher\SettingController::class, 'preUsageReport'])->name('report.teacher_pre_usage_report');

    Route::post('student_assign', [\App\Http\Controllers\Teacher\StudentController::class, 'studentAssign'])->name('student.student_assign');




});
