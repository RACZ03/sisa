<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Route;
use App\Models\State;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class RoutesController extends Controller
{
   public function index (Request $request)
   {
        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();

        $routes = Route::where('state_id', '=', $state->id )
                  ->orderBy('created_at', 'desc')
                  ->get();

        $roleTechnical = Role::where('code', '=', 'TECHNICAL')->first();

        // obtener los usuario que son tecnicos
        $users = User::where('state_id', '=', $state->id )
                    ->where('role_id', '=', $roleTechnical->id )
                    ->get();

        // load the view and pass the users
        return view('pages/routes/index', ['routes' => $routes, 'users' => $users]);
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
            $exists = Route::where('code', $value)->where('state_id', '=', $state->id )->where('id', '<>', $id)->exists();
        } else {
            $exists = Route::where('code', $value)->where('state_id', '=', $state->id )->exists();
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
            'user' => 'required|exists:users,id',
        ]);

        try{
            $routes = Route::firstOrCreate(
                ['code' => $validatedData['code']],
                [
                    'name' => $validatedData['name'],
                    'description' => $validatedData['description'] ? $validatedData['description'] : null,
                    'state_id' => State::where('code', '=', 'ACTIVE')->first()->id,
                    'user_id' => $validatedData['user'],
                ]);
            return response()->json(['message' => 'Ruta creada correctamente.', 'status' => 200], 200);
        }
            catch(QueryException $e){
            //verificar si el error es por la llave unica
            if($e -> getCode()==23000){
                if(strpos($e->getMessage(),'code'))
                {
                    return response()->json(['message' => 'El código de la ruta ya existe.', 'status' => 400], 400);
                }else{
                    return response()->json(['message' => 'Error al crear la ruta.', 'error'=> $e],400);
                }
            }else{
                    return response()->json(['message' => 'Error al crear la ruta.', 'error'=> $e],400);
            }

        }
    }

    public function update(Request $request, $id)
    {
        try {
            //obtener la ruta que se va a actualizar
            $route = Route::findOrfail($id);

            // Validar los campos del formulario antes de guardar
            $validatedData = $request->validate([
                'code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
                'user' => 'required|exists:users,id',
            ]);
            //actualizar los campos de la ruta
            $route->code = $validatedData['code'];
            $route->name = $validatedData['name'];
            $route->description = $validatedData['description'];
            $route->user_id = $validatedData['user'];
            $route->updated_at = now();
            $route->save();

            return response()->json(['message' => 'Ruta actualizada correctamente.', 'status' => 200], 200);
        }catch(ValidationException $e){
            if($e -> getCode()==23000){
                if(strpos($e->getMessage(),'code'))
                {
                return response()->json(['message' => 'El código de la ruta ya existe.', 'status' => 400], 400);
                }else{
                return response()->json(['message' => 'Error al actualizar la ruta.', 'error'=> $e],400);
                }
            }else{
                return response()->json(['message' => 'Error al actualizar la ruta.', 'error'=> $e],400);
            }
        }
    }

    public function destroy($id){
        try {
            $route = Route::findOrfail($id);

            $state= State::where('code', 'INACTIVE')->first();

            //actualizar el estado de la ruta
            $route->state_id = $state->id;
            $route->updated_at = now();
            $route->save();

            return response()->json(['message' => 'Ruta eliminada correctamente.', 'status' => 200], 200);
        }
        catch(QueryException $e)
        {
            return response()->json(['message' => 'Error al eliminar la ruta.', 'error' => $e, 'status'=>400], 400);
        }
    }

}
