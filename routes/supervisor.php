<?php


use App\Http\Controllers\GeneralController;

Route::group(['middleware' => [\App\Http\Middleware\ApprovedSupervisorMiddleware::class]], function () {
    require base_path('routes/general.php');
});

Route::group([], function () {

    Route::get('home', [\App\Http\Controllers\Supervisor\SettingController::class, 'home'])->name('home');

    Route::group(['middleware' => [\App\Http\Middleware\ApprovedSupervisorMiddleware::class]], function () {

        //Profile
        Route::get('profile/edit', [\App\Http\Controllers\Supervisor\SettingController::class, 'editProfile'])->name('edit-profile');
        Route::post('profile/update', [\App\Http\Controllers\Supervisor\SettingController::class, 'updateProfile'])->name('update-profile');
        Route::get('password/edit', [\App\Http\Controllers\Supervisor\SettingController::class, 'editPassword'])->name('edit-password');
        Route::post('password/update', [\App\Http\Controllers\Supervisor\SettingController::class, 'updatePassword'])->middleware('throttle:10,1')->name('update-password');

        //Usage Report
        Route::get('pre_usage_report', [\App\Http\Controllers\Supervisor\SettingController::class, 'preUsageReport'])->name('report.pre_usage_report');
        Route::get('usage_report', [\App\Http\Controllers\Supervisor\SettingController::class, 'usageReport'])->name('report.usage_report');

        //Teacher Usage Report
        Route::get('teacher_pre_usage_report', [\App\Http\Controllers\Supervisor\SettingController::class, 'teacherPreUsageReport'])->name('report.teacher_pre_usage_report');
        Route::get('teacher_usage_report', [\App\Http\Controllers\Supervisor\SettingController::class, 'teacherUsageReport'])->name('report.teacher_usage_report');


    });

});
