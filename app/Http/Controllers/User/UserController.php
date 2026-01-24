<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Package;
use App\Models\Payment;
use App\Models\School;
use App\Models\Story;
use App\Models\UserAssignment;
use App\Models\UserLesson;
use App\Models\UserTracker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function profile()
    {
        $title = t('Profile');
        $user = Auth::guard('web')->user()->load(['school','teacher','year','package']);
        app()->setLocale('en');
        return view('user.profile', compact('title', 'user'));
    }

    public function updateProfile(Request $request)
    {
//        dd($request->all());
        $user = Auth::guard('web')->user();
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["name"] = 'required';
        $this->validationRules["email"] = "required|unique:users,email,$user->id,id,deleted_at,NULL";
        $this->validationRules["country_code"] = 'required';
        $this->validationRules["short_country"] = 'required';
        $this->validationRules["mobile"] = ['required',
//            'phone:'.request()->get('country_code')
        ];
        $this->validationRules["year_learning"] = 'required';
        $request->validate($this->validationRules);
        $data = $request->only(['image','name','email','mobile', 'year_learning']);
        $data['mobile'] = PhoneNumber::make($request->get('mobile'))->ofCountry($request->get('short_country'));
        if ($request->hasFile('image'))
        {
            $data['image'] = uploadFile($request->file('image'), 'users')['path'];
        }
        $user->update($data);
        return $this->redirectWith(true, null, 'Successfully update personal information');

    }

    public function password()
    {
        return redirect()->route('home');

        $title = t('Change password');
//        $this->validationRules["current_password"] = 'required';
        $this->validationRules["password"] = 'required|min:6|confirmed';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('user.settings.password', compact('title',  'validator'));
    }

    public function updatePassword(Request $request)
    {
//        $this->validationRules["current_password"] = 'required';
        $this->validationRules["password"] = 'required|min:6|confirmed';

        try {
            $request->validate($this->validationRules);
            $user = Auth::guard('web')->user();
//        if(Hash::check($request->get('current_password'), $user->password)) {
                $data['password'] = bcrypt($request->get('password'));
                $user->update($data);

                // Return JSON response for AJAX requests
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Password successfully updated'
                    ]);
                }

                return $this->redirectWith(true, null, 'Password successfully updated');
//        }else{
//            return $this->redirectWith(true, null, 'Current Password Invalid', 'error');
//        }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return JSON response with validation errors for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
