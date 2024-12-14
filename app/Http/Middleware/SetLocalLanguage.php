<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SetLocalLanguage
{
    /**
     * Handle an incoming request.
     *
<<<<<<< HEAD
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
=======
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
<<<<<<< HEAD
        if(\request()->is('manager/*') && Auth::guard('manager')->check() ){
//            $locale = isAPI()? request()->header('Accept-Language') : Auth::guard('manager')->user()->lang ;
            $request['current_guard'] = 'manager';
        }else if(\request()->is('school/*') && Auth::guard('school')->check() ){
//            $locale = isAPI()? request()->header('Accept-Language') : Auth::guard('school')->user()->lang ;
            $request['current_guard'] = 'school';
            $request['school_id'] = Auth::guard('school')->user()->id;
        }else if(\request()->is('supervisor/*') && Auth::guard('supervisor')->check() ){
//            $locale = isAPI()? request()->header('Accept-Language') : Auth::guard('supervisor')->user()->lang ;
            //$request['school_id'] = $inspection_schools;//inspection schools in all request
            $request['current_guard'] = 'supervisor';
            $request['supervisor_id'] = Auth::guard('supervisor')->user()->id;
        }else if(\request()->is('teacher/*') && Auth::guard('teacher')->check() ){
//            $locale = isAPI()? request()->header('Accept-Language') : Auth::guard('teacher')->user()->lang ;
            $teacher =  Auth::guard('teacher')->user();
            $request['current_guard'] = 'teacher';
            $request['school_id'] = $teacher->school_id;
            $request['teacher_id'] = $teacher->id;
        }else{
//            $locale = isAPI()? request()->header('Accept-Language') : (session('lang') ?  session('lang'): 'ar') ;
            $request['current_guard'] = null;
        }
//        if(!$locale || !in_array($locale,['ar','en'])) $locale = 'ar';
        app()->setLocale('ar');
        //dd($locale);
=======
        if (\request()->is('manager/*') && Auth::guard('manager')->check()) {
            $request->merge(['guard' => 'manager', 'manager_id' => Auth::guard('manager')->user()->id]);
        } else if (\request()->is('school/*') && Auth::guard('school')->check()) {
            $request->merge(['guard' => 'school', 'school_id' => Auth::guard('school')->user()->id]);
        } else if (\request()->is('supervisor/*') && Auth::guard('supervisor')->check()) {
            $guard = Auth::guard('supervisor')->user();
            $request->merge(['guard' => 'supervisor', 'supervisor_id' => $guard->id, 'school_id' => $guard->school_id]);
        } else if (\request()->is('teacher/*') && Auth::guard('teacher')->check()) {
            $guard = Auth::guard('teacher')->user();
            $request->merge(['guard' => 'teacher', 'teacher_id' => $guard->id, 'school_id' => $guard->school_id]);
        } else {
            $request['guard'] = null;
        }
        app()->setLocale('ar');
>>>>>>> 7868823d29dcd1321ee7452cefbd01a89c2655b9
        return $next($request);
    }
}
