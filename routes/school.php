<?php

use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

require base_path('routes/general.php');

Route::group([], function() {

    Route::get('/home', [\App\Http\Controllers\School\SettingController::class, 'home'])->name('home');
    Route::post('statistics/chart_statistics_data',  [\App\Http\Controllers\School\SettingController::class, 'chartStatisticsData'])->name('statistics.chart_statistics_data');

    //Set Local
    Route::get('lang/{local}', [\App\Http\Controllers\School\SettingController::class, 'lang'])->name('switch-language');

    //Profile
    Route::get('profile/edit', [\App\Http\Controllers\School\SettingController::class, 'editProfile'])->name('edit-profile');
    Route::post('profile/update', [\App\Http\Controllers\School\SettingController::class, 'updateProfile'])->name('update-profile');
    Route::get('password/edit', [\App\Http\Controllers\School\SettingController::class, 'editPassword'])->name('edit-password');
    Route::post('password/update', [\App\Http\Controllers\School\SettingController::class, 'updatePassword'])->middleware('throttle:10,1')->name('update-password');


    //Lesson And Story (Hidden Control)
    Route::get('lessons', [\App\Http\Controllers\School\HiddenController::class, 'indexLessons'])->name('lessons.index');
    Route::post('hide_lessons', [\App\Http\Controllers\School\HiddenController::class, 'hideLessons'])->name('lessons.hide');
    Route::post('show_lessons', [\App\Http\Controllers\School\HiddenController::class, 'showLessons'])->name('lessons.show');
    Route::get('stories', [\App\Http\Controllers\School\HiddenController::class, 'indexStories'])->name('stories.index');
    Route::post('hide_stories', [\App\Http\Controllers\School\HiddenController::class, 'hideStories'])->name('stories.hide');
    Route::post('show_stories', [\App\Http\Controllers\School\HiddenController::class, 'showStories'])->name('stories.show');

    //Usage Report
    Route::get('pre_usage_report', [\App\Http\Controllers\School\SettingController::class, 'preUsageReport'])->name('report.pre_usage_report');
    Route::get('usage_report', [\App\Http\Controllers\School\SettingController::class, 'usageReport'])->name('report.usage_report');

    //Teacher Report
    Route::get('pre_teacher_report', [\App\Http\Controllers\School\SettingController::class, 'preTeacherReport'])->name('report.pre_teacher_report');
    Route::get('teacher_report', [\App\Http\Controllers\School\SettingController::class, 'teacherReport'])->name('report.teacher_report');




});
