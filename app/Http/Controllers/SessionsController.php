<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\User;


class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $role = Role::where('code','TECHNICAL')->first();

        $user = User::where('email',$attributes['email'])->first();

        if($user->role_id == $role->id)
        {
            return back()->withErrors(['email'=>'El usuario no tiene permiso para acceder.']);
        }


        if(Auth::attempt($attributes))
        {
            session()->regenerate();
            return redirect('dashboard')->with(['success'=>'Bienvenido']);
        }
        else{

            return back()->withErrors(['email'=>'Correo o contraseña no válidos.']);
        }
    }

    public function destroy()
    {

        Auth::logout();

        return redirect('/login')->with(['success'=>'Sesión finalizada']);
    }
}
