<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\WebController::class, 'home'])->name('main');
Route::get('page/{key}', [\App\Http\Controllers\WebController::class, 'page'])->name('page');

Route::get('/schools', [\App\Http\Controllers\WebController::class, 'schools'])->name('schools');
Route::get('students-cards-by-section', [\App\Http\Controllers\General\UserController::class, 'cards']);

//Route::get('lang/{locale}', function ($locale) {
//    session(['lang' => $locale]);
//    if (Auth::guard('teacher')->check()){
//        Auth::guard('teacher')->user()->update(['lang' => $locale,]);
//    }
//    if (Auth::guard('manager')->check()){
//        Auth::guard('manager')->user()->update(['lang' => $locale,]);
//    }
//    if (Auth::guard('supervisor')->check()){
//        Auth::guard('supervisor')->user()->update(['lang' => $locale,]);
//    }
//    if (Auth::guard('school')->check()){
//        Auth::guard('school')->user()->update(['lang' => $locale,]);
//    }
//
//    app()->setLocale($locale);
//    return back();
//})->name('switch-language');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'manager', 'as' => 'manager.'], function () {
    Route::get('/login', [\App\Http\Controllers\ManagerAuth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\ManagerAuth\LoginController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\ManagerAuth\LoginController::class, 'logout'])->name('logout');


    Route::post('/password/email', [\App\Http\Controllers\ManagerAuth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
    Route::post('/password/reset', [\App\Http\Controllers\ManagerAuth\ResetPasswordController::class, 'reset'])->name('password.email');
    Route::get('/password/reset', [\App\Http\Controllers\ManagerAuth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\ManagerAuth\ResetPasswordController::class, 'showResetForm']);
});

Route::group(['prefix' => 'school', 'as' => 'school.'], function () {
    Route::get('/login', [\App\Http\Controllers\SchoolAuth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\SchoolAuth\LoginController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\SchoolAuth\LoginController::class, 'logout'])->name('logout');

//  Route::get('/register', 'SchoolAuth\RegisterController@showRegistrationForm')->name('register');
//  Route::post('/register', 'SchoolAuth\RegisterController@register');

    Route::post('/password/email', [\App\Http\Controllers\SchoolAuth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
    Route::post('/password/reset', [\App\Http\Controllers\SchoolAuth\ResetPasswordController::class, 'reset'])->name('password.email');
    Route::get('/password/reset', [\App\Http\Controllers\SchoolAuth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\SchoolAuth\ResetPasswordController::class, 'showResetForm']);
});

Route::group(['prefix' => 'teacher', 'as' => 'teacher.'], function () {
    Route::get('/login', [\App\Http\Controllers\TeacherAuth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\TeacherAuth\LoginController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\TeacherAuth\LoginController::class, 'logout'])->name('logout');


    Route::post('/password/email', [\App\Http\Controllers\TeacherAuth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
    Route::post('/password/reset', [\App\Http\Controllers\TeacherAuth\ResetPasswordController::class, 'reset'])->name('password.email');
    Route::get('/password/reset', [\App\Http\Controllers\TeacherAuth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\TeacherAuth\ResetPasswordController::class, 'showResetForm']);
});

Route::group(['prefix' => 'supervisor', 'as' => 'supervisor.'], function () {
    Route::get('/login', [\App\Http\Controllers\SupervisorAuth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\SupervisorAuth\LoginController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\SupervisorAuth\LoginController::class, 'logout'])->name('logout');


    Route::post('/password/email', [\App\Http\Controllers\SupervisorAuth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
    Route::post('/password/reset', [\App\Http\Controllers\SupervisorAuth\ResetPasswordController::class, 'reset'])->name('password.email');
    Route::get('/password/reset', [\App\Http\Controllers\SupervisorAuth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\SupervisorAuth\ResetPasswordController::class, 'showResetForm']);
});

// The image upload of the text editor in the manager's lesson, story and
// assessment pages. It was in routes/api.php, open to anybody; it keeps its
// URL and name (the pages call route('uploadImageLesson')) but needs the
// manager login, the manager panel's middleware and the CSRF token the pages
// send.
Route::post('api/upload_image', [\App\Http\Controllers\Manager\LessonController::class, 'uploadImageLesson'])
    ->middleware(['manager', 'auth:manager', 'checkIfActive', 'session.password:manager', 'force.password:manager'])
    ->name('uploadImageLesson');

Route::group(['middleware' => ['auth', 'share_user_data']], function () {
    //Lessons
    Route::get('/home', [\App\Http\Controllers\User\LessonController::class, 'lessonsLevels'])->name('home');
    Route::get('/lessons/levels', [\App\Http\Controllers\User\LessonController::class, 'lessonsLevels'])->name('lessons.levels');
    Route::get('/lessons/{id}/sub-levels/{type}', [\App\Http\Controllers\User\LessonController::class, 'lessonsSubLevels'])->name('lessons.sub-levels');
    Route::get('/lessons/{id}/level/{type}/{level?}', [\App\Http\Controllers\User\LessonController::class, 'lessonsByLevel'])->name('lesson.lessons-by-level');
    Route::get('/lesson/{id}/level/{key}', [\App\Http\Controllers\User\LessonController::class, 'lesson'])->name('lesson.lesson-index');
    Route::get('/lessons/assignments', [\App\Http\Controllers\User\LessonController::class, 'assignments'])->name('lesson.assignments');
//    Route::post('/lessons/{id}/save/learn', 'LessonController@saveUserLearnAnswers')->name('lesson.save-user-learn-answers');
    Route::post('/lessons/{id}/tracking/{key}', [\App\Http\Controllers\User\LessonController::class, 'trackLesson'])->name('lesson.tracking');
    Route::post('/lessons/{id}/save/test', [\App\Http\Controllers\User\LessonController::class, 'saveLessonTest'])->name('lesson.save-test');
    Route::post('/lessons/{id}/save/writing-test', [\App\Http\Controllers\User\LessonController::class, 'saveLessonWritingTest'])->name('lesson.save-writing-test');
    Route::post('/lessons/{id}/save/speaking-test', [\App\Http\Controllers\User\LessonController::class, 'saveLessonSpeakingTest'])->name('lesson.save-lesson-speaking-test');

    //Stories
    Route::get('/stories/levels', [\App\Http\Controllers\User\StoryController::class, 'storiesLevels'])->name('stories.levels');
    Route::get('/stories/{id}/level', [\App\Http\Controllers\User\StoryController::class, 'storiesByLevel'])->name('story.stories-by-level');
    Route::get('/stories/{id}/story/{key}', [\App\Http\Controllers\User\StoryController::class, 'story'])->name('story.story-index');
    Route::get('/stories/assignments', [\App\Http\Controllers\User\StoryController::class, 'assignments'])->name('story.assignments');
    Route::post('/stories/{id}/tracking/{key}', [\App\Http\Controllers\User\StoryController::class, 'trackStory'])->name('story.tracking');
    Route::post('/stories/{id}/save/read', [\App\Http\Controllers\User\StoryController::class, 'saveReadRecordAnswer'])->name('save-read-record-answer');
    Route::post('/stories/{id}/save/test', [\App\Http\Controllers\User\StoryController::class, 'saveStoryTest'])->name('story.save-test');

    //Certificate
    Route::get('{type}/certificates', [\App\Http\Controllers\User\CertificateController::class, 'index'])->name('certificate.index');
    Route::get('{type}/certificates-export/{id}', [\App\Http\Controllers\User\CertificateController::class, 'certificate'])->name('certificate.get-certificate');
    Route::get('{type}/certificates-answers/{id}', [\App\Http\Controllers\User\CertificateController::class, 'certificateAnswers'])->name('certificates.answers');

    //Notifications
    Route::get('notification/{id}/read', [\App\Http\Controllers\User\NotificationController::class, 'read'])->name('notification.read');
    Route::get('notification/read-all', [\App\Http\Controllers\User\NotificationController::class, 'readAll'])->name('notification.read-all');

    //General
    Route::get('ranking', [\App\Http\Controllers\User\GeneralController::class, 'ranking'])->name('ranking');

    //User
    Route::get('profile', [\App\Http\Controllers\User\UserController::class, 'profile'])->name('user.profile');
    Route::post('profile/update', [\App\Http\Controllers\User\UserController::class, 'updateProfile'])->name('user.profile-update');
    Route::get('password', [\App\Http\Controllers\User\UserController::class, 'password'])->name('user.password');
    Route::post('password/update', [\App\Http\Controllers\User\UserController::class, 'updatePassword'])->name('user.password-update');
    Route::get('logout', [\App\Http\Controllers\User\UserController::class, 'logout'])->name('user.logout');

});


//Route::group(['namespace' => 'User', 'middleware' => ['auth']], function (){
//    Route::get('profile', 'UserController@profile')->name('profile');
//    Route::get('check_subscribe', 'UserController@checkSubscribe')->name('check_subscribe');
//    Route::get('subscribe_payment', 'UserController@subscribePayment')->name('subscribe_payment');
//    Route::post('confirm_subscribe_payment', 'UserController@checkSubscribePayment')->name('post_subscribe_payment');
//
//    Route::get('package_upgrade', 'UserController@packageUpgrade')->name('package_upgrade');
//    Route::post('confirm_package_upgrade', 'UserController@payPackageUpgrade')->name('post_package_upgrade');
//
//    Route::get('/home', 'HomeController@home')->name('home');
//    Route::get('levels', 'HomeController@levels')->name('levels');
//    Route::get('lessons_levels/{grade}/{type}', 'HomeController@subLevels')->name('lessons_levels');
//    Route::get('stories', 'HomeController@storiesLevels')->name('levels.stories');
//
//    Route::group(['middleware' => 'activeAccount'], function (){
//        Route::get('stories/{id}', 'HomeController@stories')->name('stories.list');
//        Route::get('stories/{id}/{key}', 'HomeController@story')->name('stories.show');
//        Route::post('stories/{id}/record', 'HomeController@recordStory')->name('stories.record');
//
//        Route::post('story_test/{id}/save', 'StoryController@storyTest')->name('story_test');
//        Route::get('story_test/{id}/result', 'StoryController@storyTestResult')->name('story_test_result');
//
//        Route::get('lessons/{id}/{type}', 'HomeController@lessons')->name('lessons');
//        Route::get('sub_lessons/{id}/{type}/{level}', 'HomeController@subLessons')->name('sub_lessons');
//        Route::get('lesson/{id}/{key}', 'HomeController@lesson')->name('lesson');
//
//        Route::post('lesson_test/{id}/save', 'LessonController@lessonTest')->name('lesson_test');
//        Route::post('lesson_writing_test/{id}', 'LessonController@lessonWritingTest')->name('lesson_writing_test');
//        Route::post('lesson_speaking_test/{id}', 'LessonController@lessonSpeakingTest')->name('lesson_speaking_test');
//        Route::get('lesson_test/{id}/result', 'LessonController@lessonTestResult')->name('lesson_test_result');
//
//        Route::get('certificates', 'HomeController@certificates')->name('certificates');
//        Route::get('certificate/{id}', 'HomeController@certificate')->name('certificate');
//        Route::get('new_certificate/{id}', 'HomeController@newCertificate')->name('newCertificate');
//        Route::get('certificate/{id}/answers', 'HomeController@certificateAnswers')->name('certificate.answers');
//
//        Route::get('story_certificates', 'StoryController@certificates')->name('story.certificates');
//        Route::get('story_certificate/{id}', 'StoryController@certificate')->name('story.certificate');
//        Route::get('story_certificate/{id}/answers', 'StoryController@certificateAnswers')->name('story.certificate.answers');
//
//        Route::get('motivational_certificates', 'MotivationController@certificates')->name('motivational.certificates');
//        Route::get('motivational_certificate/{id}', 'MotivationController@certificate')->name('motivational.certificate');
//
//        Route::get('assignments', 'HomeController@assignments')->name('assignments');
//        Route::get('stories_assignments', 'HomeController@storiesAssignments')->name('stories_assignments');
//
//        Route::post('track_lesson/{id}/{type}', 'UserController@trackLesson')->name('track_lesson');
//        Route::post('user_lesson/{id}', 'UserController@userLesson')->name('user_lesson');
//
//        Route::post('track_story/{id}/{type}', 'UserController@trackStory')->name('track_story');
//
//    });
//
////    Route::get('profile', 'UserController@profile')->name('profile');
////    Route::post('profile_update', 'UserController@profileUpdate')->name('profile_update');
////    Route::get('update_password', 'UserController@updatePasswordView')->name('update_password_view');
////    Route::post('update_password', 'UserController@updatePassword')->name('update_password');
//
//
//
//});
//Route::get('update_speaking', function (){
//    $speakings = \App\Models\SpeakingResult::query()->get();
//    foreach ($speakings as $speaking)
//    {
//        $speaking->update([
//            'attachment' => str_replace('speaking_result', 'record_results', $speaking->getOriginal('attachment'))
//        ]);
//    }
//});
//
//Route::get('update_users', function () {
//    $users = \App\Models\User::query()->where('school_id', 1575)->update([
//        'active_to' => \Carbon\Carbon::parse('2023-07-31'),
//    ]);
////    foreach ($users as $key => $user) {
////        $user->update([
////            'active_to' => \Carbon\Carbon::parse('2023-07-31'),
////        ]);
////    }
//    dd('done');
//
//
//});
Route::get('user/{id}/report', [\App\Http\Controllers\General\UserController::class, 'report'])->name('user.report');

Route::get('/lang/{local}', function ($local) {
    session(['lang' => $local]);
    if (Auth::guard(getGuard())->check()) {
        $user = Auth::guard(getGuard())->user()->update(['local' => $local,]);
    }
    app()->setLocale($local);
    return back();

})->name('switch-language');
