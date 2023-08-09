<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Material;
use App\Models\State;
use App\Models\Technology;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class MaterialController extends Controller
{
    public function index()
    {
        $state = State::where('code', '=', 'ACTIVE')->first();

        $technologies = Technology::where('state_id', '=', $state->id )
                    ->orderBy('created_at', 'desc')
                    ->get();

        $materials = Material::where('state_id', '=', $state->id )
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('pages/materials/index', ['materials' => $materials, 'technologies' => $technologies]);
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
            $exists = Material::where('code', $value)->where('state_id', '=', $state->id )->where('id', '<>', $id)->exists();
        } else {
            $exists = Material::where('code', $value)->where('state_id', '=', $state->id )->exists();
        }

        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {

        // Validar los campos del formulario antes de guardar
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'stock' => 'required|integer',
            'has_series' => 'required|boolean',
            'technology_id' => 'required|exists:technologies,id'
        ]);

        try {
            $state = State::where('code', '=', 'ACTIVE')->first();
            // Obtener o crear el usuario en la base de datos
            $material = Material::firstOrCreate(
                ['code' => $validatedData['code']],
                [
                    'name' => $validatedData['name'],
                    'description' => $validatedData['description'],
                    'stock' => $validatedData['stock'],
                    'has_series' => $validatedData['has_series'],
                    'technology_id' => $validatedData['technology_id'],
                    'state_id' => $state->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            return response()->json(['message' => 'Material guardado correctamente.', 'status' => 200 ], 200);
        } catch (QueryException $e) {
            // Verificar si el error fue causado por una clave única duplicada
            if ($e->getCode() == 23000) {
                // Si es una clave única duplicada, se devuelve un mensaje de error personalizado
                return response()->json(['message' => 'El código del material ya existe.', 'error' => $e], 400);
            } else {
                // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                return response()->json(['message' => 'Error al guardar el material.', 'error' => $e], 400);
            }
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Obtener el material que se desea actualizar
            $material = Material::findOrFail($id);

            // Validar los campos del formulario antes de actualizar
            $validatedData = $request->validate([
                'code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
                'stock' => 'required|integer',
                'has_series' => 'required|boolean',
                'technology_id' => 'required|exists:technologies,id',
            ]);


            // Actualizar los datos del material
            $material->code = $validatedData['code'];
            $material->name = $validatedData['name'];
            $material->description = $validatedData['description'];
            $material->stock = $validatedData['stock'];
            $material->has_series = $validatedData['has_series'];
            $material->technology_id = $validatedData['technology_id'];
            $material->updated_at = now();

            $material->save();

            return response()->json(['message' => 'Material actualizado correctamente.', 'status' => 200], 200);
        } catch (ValidationException $e) {
            // Si ocurre una excepción de validación, devolver los errores de validación
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors(), 'status' => 400], 400);
        } catch (QueryException $e) {
            // Verificar si el error fue causado por una clave única duplicada
            if ($e->getCode() == 23000) {
                // Analizar el mensaje de error para determinar si es por code
                if (strpos($e->getMessage(), 'code')) {
                    // Si es una clave única duplicada, se devuelve un mensaje de error personalizado
                    return response()->json(['message' => 'El código del material ya existe.', 'error' => $e, 'status' => 400], 400);
                } else {
                    // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                    return response()->json(['message' => 'Error al actualizar el material.', 'error' => $e, 'status' => 400], 400);
                }
            } else {
                // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                return response()->json(['message' => 'Error al actualizar el material.', 'error' => $e, 'status' => 400], 400);
            }
        }
    }

    // controllador para eliminar un usuario
    public function destroy($id)
    {
        try {
            // Obtener el usuario que se desea eliminar
            $material = Material::findOrFail($id);

            // get state code for INACTIVE
            $state = State::where('code', 'CANCELLED')->first();

            // Actualizar el estado del usuario
            $material->state_id = $state->id;
            $material->updated_at = now();
            $material->save();

            return response()->json(['message' => 'Material eliminado correctamente.', 'status' => 200], 200);
        } catch (QueryException $e) {
            // Si ocurre una excepción de validación, devolver los errores de validación
            return response()->json(['message' => 'Error al eliminar el material.', 'error' => $e, 'status' => 400], 400);
        }
    }
}
