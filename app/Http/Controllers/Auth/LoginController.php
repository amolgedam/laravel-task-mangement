<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use App\Models\User;

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

    public function login(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|regex:/[0-9]{10}/|digits:10',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('mobile', $request->get('mobile'))->first();

        if($request->get('mobile') != $user->mobile) {
            \Session::put('errors', 'Please Register First mobile number.!!');
            return back();
        }

        \Auth::login($user);

        return redirect()->route('home');
    }

    public function logout()
    {
        \Auth::logout();
        \Session::flush();
        return redirect('/login');
    }

}
