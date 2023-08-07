<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Route;
use App\Models\State;
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

      $users = User::where('role_id', '=', '4')->get();

      // load the view and pass the users
      return view('pages/routes/index', ['routes' => $routes, 'users' => $users]);
   }

   public function validateUniqueField(Request $request)
   {
      $request->validate([
         'field' => ['required', Route::in(['code'])],
         'value' => 'required',
      ]);

      $field = $request->input('field');
      $value = $request->input('value');

      $exists = Route::where('code', $value)->exists();

        return response()->json(['exists' => $exists]);
   }


   public function store(Request $request)
   {
      // Validar los campos del formulario antes de guardar
      $validatedData = $request->validate([
         'code' => 'required|string|max:255',
         'name' => 'required|string|max:255',
         'description' => 'required|string|max:255',
      ]);

      try{
        $routes = Route::firstOrCreate(
            ['code' => $validatedData['code']],
            [
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'state_id' => State::where('code', '=', 'ACTIVE')->first()->id,
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
    // Validar los campos del formulario antes de guardar
    $validatedData = $request->validate([
        'code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
    ]);

    try {
        $route = Route::find($id);
        $route->code = $validatedData['code'];
        $route->name = $validatedData['name'];
        $route->description = $validatedData['description'];
        $route->save();

        return response()->json(['message' => 'Ruta actualizada correctamente.', 'status' => 200], 200);

    } catch (QueryException $e) {
        // Verificar si el error fue causado por una clave única duplicada
        if ($e->getCode() == 23000) {
            // Analizar el mensaje de error para determinar si el code is duplicado
            if (strpos($e->getMessage(), 'code')) {
                return response()->json(['message' => 'El código de la ruta ya existe.'], 400);
            } else {
                // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                return response()->json(['message' => 'Error al actualizar la ruta.', 'error' => $e], 400);
            }

        } else {
            // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
            return response()->json(['message' => 'Error al actualizar la ruta.', 'error' => $e], 400);
        }
    }
}

public function destroy($id){
    try {
        $route = Route::findOrfail($id);

        $state= State::where('code', 'INACTIVE')->first();

        //actualizar el estado de la ruta
        $route->state_id = $state->id;
        $route->update_at = now();
        $route->save();

        return response()->json(['message' => 'Ruta eliminada correctamente.', 'status' => 200], 200);
    }
    catch(QueryException $e)
    {
        return response()->json(['message' => 'Error al eliminar la ruta.', 'error' => $e, 'status'=>400], 400);
    }
}

}
