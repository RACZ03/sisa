<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technology;
use App\Models\State;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class TechnologyController extends Controller
{
    public function index()
    {
        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();

        $technologies = Technology::where('state_id', '=', $state->id )
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('pages/technologies/index', ['technologies' => $technologies]);
    }

    public function validateUniqueField(Request $request)
    {
        $request->validate([
            'field' => ['required', Rule::in(['code'])],
            'value' => 'required',
            'id' => 'nullable|integer'
        ]);

        $field = $request->input('field');
        $value = $request->input('value');
        $id = $request->input('id');

        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();

        if ($id) {
            $exists = Technology::where('code', $value)->where('state_id', '=', $state->id )->where('id', '<>', $id)->exists();
        } else {
            $exists = Technology::where('code', $value)->where('state_id', '=', $state->id )->exists();
        }

        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        // Validar los campos del formulario antes de guardar
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:events,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            // Obtener o crear el usuario en la base de datos
            $technology = Technology::firstOrCreate(
                ['code' => $validatedData['code']],
                [
                    'name' => $validatedData['name'],
                    'description' => $validatedData['description'],
                    'state_id' => State::where('code', '=', 'ACTIVE')->first()->id,
                ]
            );

            return response()->json(['message' => 'Tecnología guardada correctamente.', 'status' => 200 ], 200);

        } catch (QueryException $e) {
            // Verificar si el error fue causado por una clave única duplicada
            if ($e->getCode() == 23000) {
                // Analizar el mensaje de error para determinar si el code is duplicado
                if (strpos($e->getMessage(), 'code')) {
                    return response()->json(['message' => 'El código de la tecnología ya existe.'], 400);
                } else {
                    // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                    return response()->json(['message' => 'Error al guardar la tecnología.', 'error' => $e], 400);
                }

            } else {
                // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                return response()->json(['message' => 'Error al guardar la tecnología.', 'error' => $e], 400);
            }
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Obtener el evento que se desea actualizar
            $technology = Technology::findOrFail($id);

            // Validar los campos del formulario antes de actualizar
            $validatedData = $request->validate([
                'code' => 'required|string|max:255|unique:events,code,' . $technology->id,
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            // Actualizar los datos del usuario
            $technology->code = $validatedData['code'];
            $technology->name = $validatedData['name'];
            $technology->description = $validatedData['description'];
            $technology->updated_at = now();

            $technology->save();

            return response()->json(['message' => 'Tecnología actualizada correctamente.', 'status' => 200], 200);
        } catch (ValidationException $e) {
            // Si ocurre una excepción de validación, devolver los errores de validación
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors(), 'status' => 400], 400);
        } catch (QueryException $e) {
            // Verificar si el error fue causado por una clave única duplicada
            if ($e->getCode() == 23000) {
                // Analizar el mensaje de error para determinar si el code is duplicado
                if (strpos($e->getMessage(), 'code')) {
                    return response()->json(['message' => 'El código de la tecnología ya existe.', 'status' => 400], 400);
                } else {
                    // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                    return response()->json(['message' => 'Error al actualizar la tecnología.', 'error' => $e, 'status' => 400], 400);
                }
            } else {
                // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                return response()->json(['message' => 'Error al actualizar la tecnología.', 'error' => $e, 'status' => 400], 400);
            }
        }
    }

    // controllador para eliminar un usuario
    public function destroy($id)
    {
        try {
            // Obtener el evento que se desea eliminar
            $technology = Technology::findOrFail($id);

            // get state code for INACTIVE
            $state = State::where('code', 'INACTIVE')->first();

            // Actualizar el estado del usuario
            $technology->state_id = $state->id;
            $technology->updated_at = now();
            $technology->save();

            return response()->json(['message' => 'Tecnología eliminada correctamente.', 'status' => 200], 200);
        } catch (QueryException $e) {
            // Si ocurre una excepción de validación, devolver los errores de validación
            return response()->json(['message' => 'Error al eliminar la tecnología.', 'error' => $e, 'status' => 400], 400);
        }
    }
}
