<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\State;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPasswordMail;
use App\Mail\UpdatePasswordMail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();
        $stateInactive = DB::table('states')->where('code', '=', 'INACTIVE')->first();


        $users = User::where('state_id', '=', $state->id )
                    ->orWhere('state_id', '=', $stateInactive->id )
                    ->orderBy('created_at', 'desc')
                    ->get();

        $roles = Role::where('code', '!=', 'SUPERADMIN')->get();

        // load the view and pass the users
        return view('pages/users/index', ['users' => $users, 'roles' => $roles]);
    }

    public function validateUniqueField(Request $request)
    {
        $request->validate([
            'field' => ['required', Rule::in(['email', 'phone'])],
            'value' => 'required',
            'id' => 'nullable|integer'
        ]);

        $field = $request->input('field');
        $value = $request->input('value');
        $id = $request->input('id');

        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();

        if ( $id ) {
            if ($field === 'email') {
                $exists = User::where('email', $value)->where('state_id', '=', $state->id )->where('id', '<>', $id)->exists();
            } elseif ($field === 'phone') {
                $exists = User::where('phone', 'LIKE', '%' . $value . '%')->where('state_id', '=', $state->id )->where('id', '<>', $id)->exists();
            } else {
                return response()->json(['error' => 'Invalid field.']);
            }
        } else {
            if ($field === 'email') {
                $exists = User::where('email', $value)->where('state_id', '=', $state->id )->exists();
            } elseif ($field === 'phone') {
                $exists = User::where('phone', 'LIKE', '%' . $value . '%')->where('state_id', '=', $state->id )->exists();
            } else {
                return response()->json(['error' => 'Invalid field.']);
            }
        }

        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        // Validar los campos del formulario antes de guardar
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'role' => 'required|exists:roles,id',
        ]);

        try {
            // Obtener o crear el usuario en la base de datos
            $user = User::firstOrCreate(['email' => $validatedData['email']], [
                'name' => strtoupper(trim($validatedData['name'])),
                'phone' => $validatedData['phone'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => $validatedData['role'],
                'state_id' => State::where('code', 'ACTIVE')->value('id'),
            ]);

            try {

                Mail::to($user->email)->send(new NewPasswordMail($user->email, $validatedData['password']));

                return response()->json(['message' => 'Usuario guardado correctamente. Se envió un correo electrónico.', 'status' => 200 ], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Usuario guardado correctamente. No se pudo enviar el correo electrónico.', 'status' => 200 ], 200);
            }


        } catch (QueryException $e) {
            // Verificar si el error fue causado por una clave única duplicada
            if ($e->getCode() == 23000) {
                // Analizar el mensaje de error para determinar si es por correo o por teléfono
                if (strpos($e->getMessage(), 'users_email_unique') !== false) {
                    return response()->json(['message' => 'El correo ya está en uso.', 'status' => 400], 400);
                } elseif (strpos($e->getMessage(), 'users_phone_unique') !== false) {
                    return response()->json(['message' => 'El teléfono ya está en uso.', 'status' => 400], 400);
                } else {
                    // Si no se puede determinar qué campo causó el error, devuelve el mensaje genérico de error
                    return response()->json(['message' => 'Error al guardar el usuario.', 'error' => $e], 400);
                }
            } else {
                // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                return response()->json(['message' => 'Error al guardar el usuario.', 'error' => $e], 400);
            }
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Obtener el usuario que se desea actualizar
            $user = User::findOrFail($id);

            // Validar los campos del formulario antes de actualizar
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:255|unique:users,phone,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                // 'password' => 'nullable|string|min:6|confirmed',
                'role' => 'required|exists:roles,id',
            ]);

            // Actualizar los datos del usuario
            $user->name = strtoupper(trim($validatedData['name']));
            $user->phone = $validatedData['phone'];
            $user->email = $validatedData['email'];
            $user->role_id = $validatedData['role'];
            $user->updated_at = now();

            // if ($validatedData['password']) {
            //     // Si se proporcionó una nueva contraseña, se actualiza
            //     $user->password = Hash::make($validatedData['password']);
            // }

            $user->save();

            return response()->json(['message' => 'Usuario actualizado correctamente.', 'status' => 200], 200);
        } catch (ValidationException $e) {
            // Si ocurre una excepción de validación, devolver los errores de validación
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors(), 'status' => 400], 400);
        } catch (QueryException $e) {
            // Verificar si el error fue causado por una clave única duplicada
            if ($e->getCode() == 23000) {
                // Analizar el mensaje de error para determinar si es por correo o por teléfono
                if (strpos($e->getMessage(), 'users_email_unique') !== false) {
                    return response()->json(['message' => 'El correo ya está en uso.', 'status' => 400], 400);
                } elseif (strpos($e->getMessage(), 'users_phone_unique') !== false) {
                    return response()->json(['message' => 'El teléfono ya está en uso.', 'status' => 400], 400);
                } else {
                    // Si no se puede determinar qué campo causó el error, devuelve el mensaje genérico de error
                    return response()->json(['message' => 'Error al actualizar el usuario.', 'error' => $e, 'status' => 400], 400);
                }
            } else {
                // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                return response()->json(['message' => 'Error al actualizar el usuario.', 'error' => $e, 'status' => 400], 400);
            }
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            // Find user
            $user = User::find($id);
            // Get code state
            $state = State::where('id', $user->state_id)->firstOrFail();

            // Verify if code is ACTIVE
            if ($state->code == 'ACTIVE') {
                // Get the ID of the INACTIVE state
                $stateInactive = State::where('code', 'INACTIVE')->first();
                $user->state_id = $stateInactive->id;
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'El estado del registro se desactivó correctamente.'
                ]);
            } else {
                // Get the ID of the ACTIVE state
                $stateActive = State::where('code', 'ACTIVE')->first();
                $user->state_id = $stateActive->id;
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'El estado del registro se activó correctamente.'
                ]);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Error al cambiar el estado del registro.',
                'error' => $e
            ]);
        }
    }

    // controllador para eliminar un usuario
    public function destroy($id)
    {
        try {
            // Obtener el usuario que se desea eliminar
            $user = User::findOrFail($id);

            // get state code for INACTIVE
            $state = State::where('code', 'CANCELLED')->first();

            // Actualizar el estado del usuario
            $user->state_id = $state->id;
            $user->updated_at = now();
            $user->save();

            return response()->json(['message' => 'Usuario eliminado correctamente.', 'status' => 200], 200);
        } catch (QueryException $e) {
            // Si ocurre una excepción de validación, devolver los errores de validación
            return response()->json(['message' => 'Error al eliminar el usuario.', 'error' => $e, 'status' => 400], 400);
        }
    }

    // create controller changePassword, receive id, password, password_confirmation, validate state ACTIVE and return message
    public function changePassword(Request $request, $id)
    {
        try {


            $state = State::where('code', 'ACTIVE')->first();
            // Obtener el usuario que se desea actualizar
            $user = User::where('id', $id)->where('state_id', $state->id)->first();

            if (!$user) {
                return response()->json(['message' => 'El usuario no existe.', 'status' => 400], 400);
            }

            // Validar los campos del formulario antes de actualizar
            $validatedData = $request->validate([
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6',
            ]);

            // Actualizar los datos del usuario
            $user->password = Hash::make($validatedData['password']);
            $user->updated_at = now();
            $user->save();

            // validate if user id is equal to auth user id and logout
            if ($id == Auth::user()->id) {
                Auth::logout();
            }

            // Enviar correo electrónico
            try {

                Mail::to($user->email)->send(new UpdatePasswordMail($user->email, $validatedData['password']));
                return response()->json(['message' => 'Contraseña actualizada correctamente. Se envió un correo electrónico.', 'status' => 200], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Contraseña actualizada correctamente. No se pudo enviar el correo electrónico.', 'status' => 200], 200);
            }

        } catch (ValidationException $e) {
            // Manejar errores de validación
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors(), 'status' => 400], 400);
        } catch (QueryException $e) {
            // Manejar errores de consulta
            return response()->json(['message' => 'Error al actualizar la contraseña.', 'error' => $e, 'status' => 400], 400);
        } catch (\Exception $e) {
            // Manejar otras excepciones
            return response()->json(['message' => 'Error al enviar el correo electrónico.', 'error' => $e->getMessage(), 'status' => 400], 400);
        }
    }

}
