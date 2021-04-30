<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function username()
    {
        return 'vemail';
    }
    
    protected function authenticated(Request $request, $user)
    {
        //
        $request->session()->forget('loggedin_username');
        $request->session()->forget('loggedin_password');

        $credentials = $this->credentials($request);
        
        session()->put('loggedin_username',  $credentials[$this->username()]);
        session()->put('loggedin_password',  $credentials['password']);
        
    }    
    
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return ['vemail'=> $request->{$this->username()}, 'password' => $request->password, 'estatus' => "Active" ];
    }
    
}
