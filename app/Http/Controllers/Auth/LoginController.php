<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

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
    public function viewLogin(){
        return view('auth.login');
    }

    public function username()
    {
        $loginData = request()->input('email');
        $fieldName = filter_var($loginData, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
        request()->merge([$fieldName => $loginData]);
        return $fieldName;
    }

    /* Login Override*/
    public function login(Request $request)
{
    $this->validateLogin($request);

    $user = User::where('login', $request->email)->first();

    if ($user && ($user->password == md5($request->password) || $user->password == hash('sha256', $request->password))) {
        Auth::login($user);
        session(['guardia' => $request->post('guardia')]);
        session(['guardia_temporal' => $request->post('guardia')]);
        return redirect('/home');
    } else {
        // Verificar si $user es nulo antes de intentar acceder a sus propiedades
        $errorMessage = $user ? 'Opps! Has ingresado mal tu contraseña' : 'Opps! Este nombre de usuario no existe';

        return redirect("/login")->with('error', $errorMessage);
    }
}

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
}
