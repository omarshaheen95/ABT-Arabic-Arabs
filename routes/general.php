<?php
Route::group([], function () {

   //General
    Route::get('getLessonsByGrade', [\App\Http\Controllers\General\GeneralController::class, 'getLessonsByGrade'])->name('getLessonsByGrade');
    Route::get('getStoriesByGrade', [\App\Http\Controllers\General\GeneralController::class, 'getStoriesByGrade'])->name('getStoriesByGrade');
    Route::get('getTeacherBySchool/{id}', [\App\Http\Controllers\General\GeneralController::class, 'getTeacherBySchool'])->name('getTeacherBySchool');
    Route::get('getSectionBySchool/{id}', [\App\Http\Controllers\General\GeneralController::class, 'getSectionBySchool'])->name('getSectionBySchool');
    Route::get('getSectionByTeacher/{id}', [\App\Http\Controllers\General\GeneralController::class, 'getSectionByTeacher'])->name('getSectionByTeacher');
    Route::get('getStudentsByGrade/{id}', [\App\Http\Controllers\General\GeneralController::class, 'getStudentsByGrade'])->name('getStudentsByGrade');


    //Supervisors
    Route::resource('supervisor', \App\Http\Controllers\General\SupervisorController::class)->except(['destroy','show']);
    Route::delete('supervisor/destroy', [\App\Http\Controllers\General\SupervisorController::class, 'destroy'])->name('supervisor.destroy');
    Route::post('supervisor/export', [\App\Http\Controllers\General\SupervisorController::class, 'export'])->name('supervisor.export');
    Route::post('supervisor/activation', [\App\Http\Controllers\General\SupervisorController::class, 'activation'])->name('supervisor.activation');
    Route::get('supervisor/{id}/login', [\App\Http\Controllers\General\SupervisorController::class, 'login'])->name('supervisor.login');
    Route::post('supervisor/reset-passwords', [\App\Http\Controllers\General\SupervisorController::class, 'resetPasswords'])->name('supervisor.reset-passwords');
    Route::post('supervisor/force-password-change', [\App\Http\Controllers\General\SupervisorController::class, 'forcePasswordChange'])->name('supervisor.force-password-change');


    //Roles And Permission
    Route::resource('role',\App\Http\Controllers\General\RoleAndPermission\RoleController::class)->except(['destroy']);
    Route::delete('role', [\App\Http\Controllers\General\RoleAndPermission\RoleController::class, 'destroy'])->name('role.destroy');
    Route::resource('permission',\App\Http\Controllers\General\RoleAndPermission\PermissionController::class)->except(['destroy']);
    Route::delete('permission',[\App\Http\Controllers\General\RoleAndPermission\PermissionController::class, 'destroy'])->name('permission.destroy');
//    Route::get('user_role_and_permission','RoleAndPermission\UserRoleAndPermissionController@index')->name('user_role_and_permission.index');
    Route::get('user_role_and_permission/{user_guard}/{id}/edit',[\App\Http\Controllers\General\RoleAndPermission\UserRoleAndPermissionController::class, 'edit'])->name('user_role_and_permission.edit');
    Route::post('user_role_and_permission/{id}/update',[\App\Http\Controllers\General\RoleAndPermission\UserRoleAndPermissionController::class, 'update'])->name('user_role_and_permission.update');


    //Teacher
    Route::resource('teacher', \App\Http\Controllers\General\TeacherController::class)->except(['destroy','show']);
    Route::delete('teacher/destroy', [\App\Http\Controllers\General\TeacherController::class, 'destroy'])->name('teacher.destroy');
//    Route::get('teacher/update_statistics', 'SettingController@updateTeacherStatistics')->name('teacher.updateTeacherStatistics');
    Route::post('teacher/activation', [\App\Http\Controllers\General\TeacherController::class, 'activation'])->name('teacher.activation');
    Route::post('teacher/delete_students', [\App\Http\Controllers\General\TeacherController::class, 'deleteStudents'])->name('teacher.delete_students');
    Route::post('teacher/export', [\App\Http\Controllers\General\TeacherController::class, 'exportTeachersExcel'])->name('teacher.export_teachers_excel');
    Route::get('teacher/{id}/login', [\App\Http\Controllers\General\TeacherController::class, 'login'])->name('teacher.login');
    Route::get('tracking_teachers', [\App\Http\Controllers\General\TeacherController::class, 'teachersTracking'])->name('teacher.tracking');
    Route::post('tracking_teachers_export', [\App\Http\Controllers\General\TeacherController::class, 'teachersTrackingExport'])->name('teacher.tracking_export');
    Route::get('tracking_teachers_report/{id}', [\App\Http\Controllers\General\TeacherController::class, 'teachersTrackingReport'])->name('teacher.tracking_report');
    Route::post('teacher/reset-passwords', [\App\Http\Controllers\General\TeacherController::class, 'resetPasswords'])->name('teacher.reset-passwords');
    Route::post('teacher/force-password-change', [\App\Http\Controllers\General\TeacherController::class, 'forcePasswordChange'])->name('teacher.force-password-change');


    //User
    Route::resource('user', \App\Http\Controllers\General\UserController::class)->except(['destroy','show']);
//    Route::get('duplicate_user', 'UserController@duplicateIndex')->name('user.duplicate_user');
//    Route::delete('duplicate_user/{id}', 'UserController@destroyDuplicate')->name('user.delete_duplicate_user');
    Route::post('user/export', [\App\Http\Controllers\General\UserController::class, 'export'])->name('user.export');
    Route::post('user/reset-passwords', [\App\Http\Controllers\General\UserController::class, 'resetPasswords'])->name('user.reset-passwords');
    Route::get('user/{id}/card', [\App\Http\Controllers\General\UserController::class, 'userCard'])->name('user.card');
    Route::get('user/cards_and_qr', [\App\Http\Controllers\General\UserController::class, 'cards'])->name('user.cards-export');
    Route::get('user/cards_and_qr_file', [\App\Http\Controllers\General\UserController::class, 'studentsImportFileCards'])->name('user.studentsImportFileCards');
    Route::post('user/cards_by_section', [\App\Http\Controllers\General\UserController::class, 'studentsCardsBySection'])->name('user.cards-by-section');
    Route::get('user/lesson_review/{id}', [\App\Http\Controllers\General\UserController::class, 'lessonReview'])->name('user.review');
    Route::get('user/story_review/{id}', [\App\Http\Controllers\General\UserController::class, 'storyReview'])->name('user.story_review');
    Route::get('user/report/{id}', [\App\Http\Controllers\General\UserController::class, 'report'])->name('user.report');
    Route::get('user/{id}/login', [\App\Http\Controllers\General\UserController::class, 'login'])->name('user.login');
    Route::delete('user/destroy', [\App\Http\Controllers\General\UserController::class, 'destroy'])->name('user.destroy');
    Route::post('user/activation', [\App\Http\Controllers\General\UserController::class, 'userActivation'])->name('user.activation');
    Route::post('user/update_grades', [\App\Http\Controllers\General\UserController::class, 'updateGrades'])->name('user.update_grades');
    Route::post('user/update_learning_years', [\App\Http\Controllers\General\UserController::class, 'updateLearningYears'])->name('user.update_learning_years');
    Route::post('assigned_to_teacher', [\App\Http\Controllers\General\UserController::class, 'assignedToTeacher'])->name('user.assigned_to_teacher');
    Route::post('unassigned_user_teacher', [\App\Http\Controllers\General\UserController::class, 'unassignedUserTeacher'])->name('user.unassigned_user_teacher');
    Route::post('user/restore', [\App\Http\Controllers\General\UserController::class, 'restoreUser'])->name('user.restore');
    Route::get('my_students', [\App\Http\Controllers\General\UserController::class, 'myStudents'])->name('my-students');
    Route::post('/pdfReports', [\App\Http\Controllers\General\UserController::class, 'pdfReports'])->name('reports.pdfReports');
//
//
    //Lessons Test
    Route::resource('lessons_tests', \App\Http\Controllers\General\LessonTestController::class)->except(['destroy','show']);
    Route::delete('lessons_tests/destroy', [\App\Http\Controllers\General\LessonTestController::class, 'destroy'])->name('lessons_tests.destroy');
    Route::get('lessons_tests/preview_answers/{id}', [\App\Http\Controllers\General\LessonTestController::class, 'preview'])->name('lessons_tests.preview_answers');
    //For [writing,speaking]
    Route::get('lessons_tests/correcting_feedback/{id}', [\App\Http\Controllers\General\LessonTestController::class, 'correctingAndFeedbackView'])->name('lessons_tests.correcting_feedback_view');
    Route::post('lessons_tests/correcting_feedback/{id}', [\App\Http\Controllers\General\LessonTestController::class, 'correctingAndFeedback'])->name('lessons_tests.correcting_feedback');
    Route::get('lessons_tests/correcting/{id}', [\App\Http\Controllers\General\LessonTestController::class, 'correctingUserTestView'])->name('lessons_tests.correcting_view');
    Route::post('lessons_tests/correcting/{id}', [\App\Http\Controllers\General\LessonTestController::class, 'correctingUserTest'])->name('lessons_tests.correcting');
    Route::post('lessons_tests/auto_correcting', [\App\Http\Controllers\General\LessonTestController::class, 'autoCorrectingUsersTests'])->name('lessons_tests.auto_correcting');
    Route::get('lessons_tests/certificate/{id}', [\App\Http\Controllers\General\LessonTestController::class, 'certificate'])->name('lessons_tests.certificate');
    Route::post('lessons_tests/export', [\App\Http\Controllers\General\LessonTestController::class, 'export'])->name('lessons_tests.export');

    //Story Test
    Route::resource('stories_tests', \App\Http\Controllers\General\StoryTestController::class)->except(['destroy','show']);
    Route::get('stories_tests/correcting/{id}', [\App\Http\Controllers\General\StoryTestController::class, 'correctingView'])->name('stories_tests.correcting_view');
    Route::post('stories_tests/correcting/{id}', [\App\Http\Controllers\General\StoryTestController::class, 'correcting'])->name('stories_tests.correcting');
    Route::post('stories_tests/auto_correcting', [\App\Http\Controllers\General\StoryTestController::class, 'autoCorrectingTests'])->name('stories_tests.auto_correcting');
    Route::delete('stories_tests/destroy', [\App\Http\Controllers\General\StoryTestController::class, 'destroy'])->name('stories_tests.destroy');
    Route::get('stories_tests/{id}/certificate', [\App\Http\Controllers\General\StoryTestController::class, 'certificate'])->name('stories_tests.certificate');
    Route::post('stories_tests/export', [\App\Http\Controllers\General\StoryTestController::class, 'export'])->name('stories_tests.export');

    //Story Record
    Route::resource('stories_records', \App\Http\Controllers\General\StoryRecordController::class)->except(['destroy']);
    Route::delete('stories_records/destroy', [\App\Http\Controllers\General\StoryRecordController::class, 'destroy'])->name('stories_records.destroy');
    Route::post('stories_records/export', [\App\Http\Controllers\General\StoryRecordController::class, 'export'])->name('stories_records.export');

    //Lesson Assignment
    Route::resource('lesson_assignment',\App\Http\Controllers\General\LessonAssignmentController::class)->except(['destroy']);
    Route::delete('lesson_assignment/destroy', [\App\Http\Controllers\General\LessonAssignmentController::class, 'destroy'])->name('lesson_assignment.destroy');
    Route::post('lesson_assignment/export', [\App\Http\Controllers\General\LessonAssignmentController::class, 'export'])->name('lesson_assignment.export');

    //User Lessons Assignments
    Route::resource('user_lesson_assignment', \App\Http\Controllers\General\UserLessonAssignmentController::class)->only(['index']);
    Route::delete('user_lesson_assignment/destroy', [\App\Http\Controllers\General\UserLessonAssignmentController::class, 'destroy'])->name('user_lesson_assignment.destroy');
    Route::post('user_lesson_assignment/export', [\App\Http\Controllers\General\UserLessonAssignmentController::class, 'export'])->name('user_lesson_assignment.export');

    //Story Assignment
    Route::resource('story_assignment',\App\Http\Controllers\General\StoryAssignmentController::class)->except(['destroy']);
    Route::delete('story_assignment/destroy', [\App\Http\Controllers\General\StoryAssignmentController::class, 'destroy'])->name('story_assignment.destroy');
    Route::post('story_assignment/export', [\App\Http\Controllers\General\StoryAssignmentController::class, 'export'])->name('story_assignment.export');

    //User Stories Assignments
    Route::resource('user_story_assignment', \App\Http\Controllers\General\UserStoryAssignmentController::class)->only(['index']);
    Route::delete('user_story_assignment/destroy', [\App\Http\Controllers\General\UserStoryAssignmentController::class, 'destroy'])->name('user_story_assignment.destroy');
    Route::post('user_story_assignment/export', [\App\Http\Controllers\General\UserStoryAssignmentController::class, 'export'])->name('user_story_assignment.export');
//
    //Motivational Certificate
    Route::resource('motivational_certificates', \App\Http\Controllers\General\MotivationalCertificateController::class)->except(['destroy', 'edit', 'update']);
    Route::delete('motivational_certificates/destroy', [\App\Http\Controllers\General\MotivationalCertificateController::class, 'destroy'])->name('motivational_certificates.destroy');
    Route::post('motivational_certificates/export', [\App\Http\Controllers\General\MotivationalCertificateController::class, 'export'])->name('motivational_certificates.export');

    //Roles And Permission
    Route::resource('role',\App\Http\Controllers\General\RoleAndPermission\RoleController::class)->except(['destroy']);
    Route::delete('role', [\App\Http\Controllers\General\RoleAndPermission\RoleController::class, 'destroy'])->name('role.destroy');
    Route::resource('permission',\App\Http\Controllers\General\RoleAndPermission\PermissionController::class)->except(['destroy']);
    Route::delete('permission',[\App\Http\Controllers\General\RoleAndPermission\PermissionController::class, 'destroy'])->name('permission.destroy');
//    Route::get('user_role_and_permission','RoleAndPermission\UserRoleAndPermissionController@index')->name('user_role_and_permission.index');
    Route::get('user_role_and_permission/{user_guard}/{id}/edit',[\App\Http\Controllers\General\RoleAndPermission\UserRoleAndPermissionController::class, 'edit'])->name('user_role_and_permission.edit');
    Route::post('user_role_and_permission/{id}/update',[\App\Http\Controllers\General\RoleAndPermission\UserRoleAndPermissionController::class, 'update'])->name('user_role_and_permission.update');


    //Reports
    //Usage Report
//    Route::get('pre_usage_report', 'ReportController@preUsageReport')->name('report.pre_usage_report');
//    Route::get('usage_report', 'ReportController@usageReport')->name('report.usage_report');
//    Route::post('usage_report_excel', 'ReportController@exportUsageReport')->name('report.usage_report_excel');

});


