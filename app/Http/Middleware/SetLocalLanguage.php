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
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
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
        return $next($request);
    }
}
