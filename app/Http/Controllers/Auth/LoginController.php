<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials()
    {
        $username = $this->username();
        $credentials = request()->only($username, 'password');
        if (isset($credentials[$username])) {
            $credentials[$username] = strtolower($credentials[$username]);
        }
        return $credentials;
    }

    //check if user is not archived before login
    protected function attemptLogin(Request $request)
    {
        // Retrieve the user based on the email provided
        $user = \App\Models\User::where(DB::raw('LOWER(email)'), $request->email)->first();

        // Check if the user exists and if they are active
        if ($user && $user->archived) {
            // Optionally, you can log this attempt or notify the user that their account is inactive
            return false;
        }

        // Attempt to log the user in with the credentials provided
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
}
