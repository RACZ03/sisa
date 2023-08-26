<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\View;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPasswordMail;
use Illuminate\Support\Facades\Hash;

class ResetController extends Controller
{
    public function create()
    {
        return view('session/reset-password/sendEmail');

    }

    public function sendEmail(Request $request)
    {
        try {

            // validate email exists
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ]);

            var_dump($request->email);

            $state = State::where('code', 'ACTIVE')->first();

            $user = User::where('email', $request->email)->where('state_id', $state->id)->first();

            $user->password = Hash::make('12345678');
            $user->save();

            // send mail
            try {
                Mail::to($user->email)->send(new NewPasswordMail($user->email, '12345678'));
                return redirect()->back()->with(['success' => 'Contraseña actualizada. Se envió un correo electrónico con la nueva contraseña.']);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['msg2' => 'Contraseña actualizada. No se pudo enviar el correo electrónico.']);
            }


        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['msg2' => 'Valor ingresado vacío o no válido']);
        }
    }

    public function resetPass($token)
    {
        return view('session/reset-password/resetPassword', ['token' => $token]);
    }
}
