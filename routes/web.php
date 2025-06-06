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

Route::get('/', 'WebController@home')->name('main');
Route::get('page/{key}', 'WebController@page')->name('page');

Route::get('/schools', 'WebController@schools')->name('schools');
Route::get('students-cards-by-section', [\App\Http\Controllers\General\UserController::class,'cards']);

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

Route::group(['prefix' => 'manager','namespace' => 'ManagerAuth','as' => 'manager.'], function () {
  Route::get('/login', 'LoginController@showLoginForm')->name('login');
  Route::post('/login', 'LoginController@login');
  Route::post('/logout', 'LoginController@logout')->name('logout');


  Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'school','namespace' => 'SchoolAuth','as' => 'school.'], function () {
  Route::get('/login', 'LoginController@showLoginForm')->name('login');
  Route::post('/login', 'LoginController@login');
  Route::post('/logout', 'LoginController@logout')->name('logout');

//  Route::get('/register', 'SchoolAuth\RegisterController@showRegistrationForm')->name('register');
//  Route::post('/register', 'SchoolAuth\RegisterController@register');

  Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'teacher','namespace' => 'TeacherAuth','as' => 'teacher.'], function () {
  Route::get('/login', 'LoginController@showLoginForm')->name('login');
  Route::post('/login', 'LoginController@login');
  Route::post('/logout', 'LoginController@logout')->name('logout');


  Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'supervisor','namespace' => 'SupervisorAuth','as' => 'supervisor.'], function () {
  Route::get('/login', 'LoginController@showLoginForm')->name('login');
  Route::post('/login', 'LoginController@login');
  Route::post('/logout', 'LoginController@logout')->name('logout');


  Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm');
});

Route::get('migrate', function (){
    \Illuminate\Support\Facades\Artisan::call('migrate');
});
Route::get('view', function (){
    \Illuminate\Support\Facades\Artisan::call('view:clear');
});
Route::get('command', function (){
    \Illuminate\Support\Facades\Artisan::call('schedule:work');
});
Route::get('cache', function (){
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
});


Route::group(['namespace' => 'User', 'middleware' => ['auth']], function (){
    Route::get('check_subscribe', 'UserController@checkSubscribe')->name('check_subscribe');
    Route::get('subscribe_payment', 'UserController@subscribePayment')->name('subscribe_payment');
    Route::post('confirm_subscribe_payment', 'UserController@checkSubscribePayment')->name('post_subscribe_payment');

    Route::get('package_upgrade', 'UserController@packageUpgrade')->name('package_upgrade');
    Route::post('confirm_package_upgrade', 'UserController@payPackageUpgrade')->name('post_package_upgrade');

    Route::get('/home', 'HomeController@home')->name('home');
    Route::get('levels', 'HomeController@levels')->name('levels');
    Route::get('lessons_levels/{grade}/{type}', 'HomeController@subLevels')->name('lessons_levels');
    Route::get('stories', 'HomeController@storiesLevels')->name('levels.stories');

    Route::group(['middleware' => 'activeAccount'], function (){
        Route::get('stories/{id}', 'HomeController@stories')->name('stories.list');
        Route::get('stories/{id}/{key}', 'HomeController@story')->name('stories.show');
        Route::post('stories/{id}/record', 'HomeController@recordStory')->name('stories.record');

        Route::post('story_test/{id}/save', 'StoryController@storyTest')->name('story_test');
        Route::get('story_test/{id}/result', 'StoryController@storyTestResult')->name('story_test_result');

        Route::get('lessons/{id}/{type}', 'HomeController@lessons')->name('lessons');
        Route::get('sub_lessons/{id}/{type}/{level}', 'HomeController@subLessons')->name('sub_lessons');
        Route::get('lesson/{id}/{key}', 'HomeController@lesson')->name('lesson');

        Route::post('lesson_test/{id}/save', 'LessonController@lessonTest')->name('lesson_test');
        Route::post('lesson_writing_test/{id}', 'LessonController@lessonWritingTest')->name('lesson_writing_test');
        Route::post('lesson_speaking_test/{id}', 'LessonController@lessonSpeakingTest')->name('lesson_speaking_test');
        Route::get('lesson_test/{id}/result', 'LessonController@lessonTestResult')->name('lesson_test_result');

        Route::get('certificates', 'HomeController@certificates')->name('certificates');
        Route::get('certificate/{id}', 'HomeController@certificate')->name('certificate');
        Route::get('new_certificate/{id}', 'HomeController@newCertificate')->name('newCertificate');
        Route::get('certificate/{id}/answers', 'HomeController@certificateAnswers')->name('certificate.answers');

        Route::get('story_certificates', 'StoryController@certificates')->name('story.certificates');
        Route::get('story_certificate/{id}', 'StoryController@certificate')->name('story.certificate');
        Route::get('story_certificate/{id}/answers', 'StoryController@certificateAnswers')->name('story.certificate.answers');

        Route::get('motivational_certificates', 'MotivationController@certificates')->name('motivational.certificates');
        Route::get('motivational_certificate/{id}', 'MotivationController@certificate')->name('motivational.certificate');

        Route::get('assignments', 'HomeController@assignments')->name('assignments');
        Route::get('stories_assignments', 'HomeController@storiesAssignments')->name('stories_assignments');

        Route::post('track_lesson/{id}/{type}', 'UserController@trackLesson')->name('track_lesson');
        Route::post('user_lesson/{id}', 'UserController@userLesson')->name('user_lesson');

        Route::post('track_story/{id}/{type}', 'UserController@trackStory')->name('track_story');

    });

//    Route::get('profile', 'UserController@profile')->name('profile');
//    Route::post('profile_update', 'UserController@profileUpdate')->name('profile_update');
//    Route::get('update_password', 'UserController@updatePasswordView')->name('update_password_view');
//    Route::post('update_password', 'UserController@updatePassword')->name('update_password');



});
Route::get('update_speaking', function (){
    $speakings = \App\Models\SpeakingResult::query()->get();
    foreach ($speakings as $speaking)
    {
        $speaking->update([
            'attachment' => str_replace('speaking_result', 'record_results', $speaking->getOriginal('attachment'))
        ]);
    }
});

Route::get('update_users', function () {
    $users = \App\Models\User::query()->where('school_id', 1575)->update([
        'active_to' => \Carbon\Carbon::parse('2023-07-31'),
    ]);
//    foreach ($users as $key => $user) {
//        $user->update([
//            'active_to' => \Carbon\Carbon::parse('2023-07-31'),
//        ]);
//    }
    dd('done');


});
Route::get('user/{id}/report', 'General\UserController@report')->name('user.report');

Route::get('/lang/{local}', function ($local) {
    session(['lang' => $local]);
    if (Auth::guard(getGuard())->check()){
        $user = Auth::guard(getGuard())->user()->update(['local' => $local,]);
    }
    app()->setLocale($local);
    return back();

})->name('switch-language');
