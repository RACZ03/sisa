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

class UserController extends Controller
{
    public function index(Request $request)
    {
        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();
        $role = DB::table('roles')->where('code', '=', 'SUPERADMIN')->first();

        $users = User::where('state_id', '=', $state->id )
                    ->where('role_id', '!=', $role->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $roles = Role::where('code', '!=', 'SUERADMIN')->get();

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
            'phone' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'role' => 'required|exists:roles,id',
        ]);

        try {
            // Obtener o crear el usuario en la base de datos
            $user = User::firstOrCreate(['email' => $validatedData['email']], [
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => $validatedData['role'],
                'state_id' => State::where('code', 'ACTIVE')->value('id'),
            ]);

            return response()->json(['message' => 'Usuario guardado correctamente.', 'status' => 200 ], 200);
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
                'phone' => 'required|string|max:255|unique:users,phone,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'required|exists:roles,id',
            ]);

            // Actualizar los datos del usuario
            $user->name = $validatedData['name'];
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

    // controllador para eliminar un usuario
    public function destroy($id)
    {
        try {
            // Obtener el usuario que se desea eliminar
            $user = User::findOrFail($id);

            // get state code for INACTIVE
            $state = State::where('code', 'INACTIVE')->first();

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

}
