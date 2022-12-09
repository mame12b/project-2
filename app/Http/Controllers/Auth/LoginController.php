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

    /**
     * Login users
     *
     * @param \Illuminate\Http\Request $request
     */
    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            auth()->user()->update([
                'last_login' => now()
            ]);
            if (auth()->user()->type == 'admin') {
                if($request->next){
                    return redirect($request->next);
                }else{
                    return redirect()->route('admin.home');
                }
            }else if (auth()->user()->type == 'school') {
                if($request->next){
                    return redirect($request->next);
                }else{
                    return redirect()->route('school.home');
                }
            }else if (auth()->user()->type == 'department') {
                if($request->next){
                    return redirect($request->next);
                }else{
                    return redirect()->route('department.home');
                }
            }else{
                if(auth()->user()->is_staff == 1){
                    auth()->logout();
                    return redirect()->route('login')
                            ->with('error','Role Not Defiend.');
                }else{
                    if($request->next){
                        return redirect($request->next);
                    }else{
                        return redirect()->route('user.home');
                    }
                }
            }
        }else{
            return redirect()->route('login')
                ->with('error','Invalid Credential.');
        }

    }
}
