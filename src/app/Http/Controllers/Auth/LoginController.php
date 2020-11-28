<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Log;
class LoginController extends Controller
{
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

    protected function authenticated(Request $request, $user)
    {
        # obtengo roles del usuario, traigo el primero.
        $role = $user->roles()->first();

        if (!$role) {
            abort(500);
        }

        session([
            'id_participante' => $role->pivot->id_Participante,
            'role' => $role->Nombre
        ]);
    }
}
