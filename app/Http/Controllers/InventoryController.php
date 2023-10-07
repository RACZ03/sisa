<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\Material;
use App\Models\Technology;
use App\Models\Event;
use App\Models\State;
use App\Models\User;
use App\Models\Route;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;


class InventoryController extends Controller
{
    public function index()
    {
        // get states code ACTIVE and INCACTIVE
        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();
        $stateInactive = DB::table('states')->where('code', '=', 'CANCELLED')->first();
        // get inventory data order by created_at desc and state active and inactive
        $inventories = Inventory::where('state_id', $state->id)->orWhere('state_id', $stateInactive->id)->orderBy('created_at', 'desc')->get();

        return view('pages/inventory/index', ['inventories' => $inventories]);
    }

    public function show($id)
    {
        // get inventory data
        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();
        $inventory = Inventory::find($id);
        // find details with inventory id and state active
        $inventory_details = InventoryDetail::where('inventory_id', $id)->where('state_id', $state->id)->get();
        $user = Auth::user();

        // si no existe el inventario return index
        if (!$inventory) {
            return redirect()->route('inventory');
        }

        return view('pages/inventory/show', ['userAuth' => $user, 'inventory' => $inventory, 'inventory_details' => $inventory_details]);
    }

    // controller create inventory page
    public function create()
    {
        // get states code ACTIVE
        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();
        $role = Role::where('code', 'TECHNICAL')->first();

        // get inventory data
        $events = Event::where('state_id', $state->id)->get();
        $technologies = Technology::where('state_id', $state->id)->get();
        $materials = Material::where('state_id', $state->id)->get();
        $technicals = User::where('role_id', $role->id)->get();
        $routes = Route::where('state_id', $state->id)->get();

        $technicalsInRoutes = $technicals->whereIn('user_id', $routes->pluck('route_id'));
        // filter technicals with asigned routes


        // get user auth
        $user = Auth::user();

        // Obtener nombre del usuario logueado
        return view('pages/inventory/create',
            ['userAuth' => $user, 'events' => $events, 'technologies' => $technologies, 'materials' => $materials, 'technicals' => $technicalsInRoutes, 'routes' => $routes]
        );
    }

    // controller findSeries
    public function validateUniqueField(Request $request)
{
        $request->validate([
            'series' => 'required',
            'material_id' => 'required|exists:materials,id',
        ]);

        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();
        $value = $request->input('series');
        $material_id = $request->input('material_id');
        $exists = InventoryDetail::where('series', $value)
                                ->where('material_id', '=', $material_id)
                                ->where('state_id', '=', $state->id)->first();

        return response()->json(['exists' => $exists]);
    }

    // controller store inventory
    public function store(Request $request)
    {
        // Iniciar una transacción
        DB::beginTransaction();

        // Validar los campos del formulario antes de guardar
        $validatedData = $request->validate([
            'date' => 'required|date',
            'event_id' => 'required|exists:events,id',
            'technology_id' => 'required|exists:technologies,id',
            'route_id' => 'required|exists:routes,id',
            'technical_id' => 'required|exists:users,id',
            'detalle' => 'required|array|min:1',
        ]);

        try {
            $state = State::where('code', '=', 'ACTIVE')->first();
            $user_session_id = Auth::id();

            // generar codigo utilizando la funcion generateCode()
            $codigo = $this->generarCodigoUnico();

            $inventory = new Inventory();
            $inventory->code = $codigo;
            $inventory->date = $request->input('date');
            $inventory->event_id = $request->input('event_id');
            $inventory->technology_id = $request->input('technology_id');
            $inventory->route_id = $request->input('route_id');
            $inventory->user_id = $request->input('technical_id');
            $inventory->creator_user_id = $user_session_id;
            $inventory->state_id = $state->id;
            $inventory->save();

            // get id inventory
            $inventory_id = $inventory->id;

            // recorrer el array de detalle
            foreach ($request->input('detalle') as $key => $value) {

                $inventory_detail = new InventoryDetail();
                $inventory_detail->code = $value['code'];
                $inventory_detail->material_id = $value['material_id'];

                // obtener stock material
                $material = Material::find($value['material_id']);
                // obtener Event by code load
                $event = Event::where('code', '=', 'LOAD')->first();

                // validar si el stock del manterial es negativo y devolver mensaje personalizado
                if ($material->stock < 0) {
                    // Hacer rollback y devolver un error
                    DB::rollBack();
                    return response()->json(['message' => 'Error al guardar el movimiento. El stock del material no puede ser negativo.', 'status' => 400], 400);
                }


                $inventory_detail->old_stock = $material->stock;

                // si el evento es carga, sumar al new_stock

                // consultar el ultimo detalle que pertenece al mismo material
                $last_detail = InventoryDetail::where('material_id', $value['material_id'])->where('state_id', $state->id)->orderBy('id', 'desc')->first();

                $value['series'] = str_replace(' ', '', $value['series']);

                if ($request->input('event_id') == $event->id) {
                    $inventory_detail->new_stock = $material->stock + $value['count'];


                    // verificar si existe last_detail
                    if ($last_detail && $value['series'] != '') {
                        // obtener el valor existing_series
                        $inventory_detail->existing_series = $last_detail->existing_series.','.$value['series'];

                    } else {
                        $inventory_detail->existing_series = $value['series'];
                    }
                } else {
                    $inventory_detail->new_stock = $material->stock - $value['count'];

                    // Verificar si el stock después del débito sería menor que 0
                    if ($inventory_detail->new_stock < 0) {
                        // Hacer rollback y devolver un error
                        DB::rollBack();
                        return response()->json(['message' => 'Error al guardar el movimiento. El stock del material no puede ser negativo.', 'status' => 400], 400);
                    }

                    // verificar si existe last_detail
                    if ($last_detail && $last_detail->existing_series != null && $last_detail->existing_series != '' && $value['series'] != '' ) {
                        // obtener el valor existing_series
                        $existing_series_array = explode(',', $last_detail->existing_series);

                        // remover la series del inventario de la lista existing_series_array
                        $existing_series_array = array_diff($existing_series_array, [$value['series']]);

                        // convertir el array a string
                        $inventory_detail->existing_series = implode(',', $existing_series_array);

                    } else {
                        $inventory_detail->existing_series = $value['series'];
                    }
                }

                $inventory_detail->count = $value['count'];
                $inventory_detail->series = $value['series'];


                $inventory_detail->inventory_id = $inventory_id;
                $inventory_detail->state_id = $state->id;
                $inventory_detail->save();

                // Verificar si el save se ejecutó correctamente, actualizar el stock del material
                if ($inventory_detail->id) {
                    $material = Material::find($value['material_id']);
                    if ($request->input('event_id') == $event->id) {
                        $material->stock = $material->stock + $value['count'];
                    } else {
                        $material->stock = $material->stock - $value['count'];
                    }
                    $material->save();
                }
            }

            // Commit si todo está correcto
            DB::commit();

            return response()->json(['message' => 'El movimiento se guardó correctamente.', 'status' => 200], 201);
        } catch (QueryException $e) {
            // Hacer rollback en caso de una excepción
            DB::rollBack();

            // Verificar si el error fue causado por una clave única duplicada
            if ($e->getCode() == 23000) {

                // Si es una clave única duplicada, se devuelve un mensaje de error personalizado
                return response()->json(['message' => 'Error al guardar el movimiento. El código del movimiento ya existe.', 'error' => $e], 400);
            } else {
                // validar si el error es por old_stock o new_stock negativo y devolver mensaje personalizado
                if (strpos($e->getMessage(), 'old_stock') !== false || strpos($e->getMessage(), 'new_stock') !== false) {
                    return response()->json(['message' => 'Error al guardar el movimiento. El stock del material no puede ser negativo.', 'error' => $e], 400);
                }
                // Si no es una clave única duplicada, se devuelve el mensaje genérico de error
                return response()->json(['message' => 'Error al guardar el movimiento.', 'error' => $e], 400);
            }
        }
    }


    public function generarCodigoUnico()
    {
        do {
            $codigo = strtoupper(Str::random(15));
            $inventory_code = Inventory::where('code', $codigo)->first();
        } while ($inventory_code);

        return $codigo;
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            // Find inventory
            $inventory = Inventory::find($id);
            // Get code state
            $state = State::where('id', $inventory->state_id)->firstOrFail();

            // Verify if code is ACTIVE
            if ($state->code == 'ACTIVE') {
                // Get the ID of the INACTIVE state
                $stateInactive = State::where('code', 'CANCELLED')->first();

                // change state details inventario by inventario id
                $inventory_details = InventoryDetail::where('inventory_id', $id)->get();

                foreach ($inventory_details as $key => $value) {

                    // obtener material by id
                    $material = Material::find($value->material_id);

                    // obtener Event by code load
                    $event = Event::where('code', '=', 'LOAD')->first();

                    // si el evento es carga, restar al stock
                    if ($inventory->event_id == $event->id) {
                        $material->stock = $material->stock - $value->count;
                    } else {
                        $material->stock = $material->stock + $value->count;
                    }

                    $material->save();

                    $value->state_id = $stateInactive->id;
                    $value->save();
                }

                $inventory->state_id = $stateInactive->id;
                $inventory->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'El estado del registro se anuló correctamente.',
                ]);
            } else {
                // Get the ID of the ACTIVE state
                $stateActive = State::where('code', 'ACTIVE')->first();
                $inventory->state_id = $stateActive->id;
                $inventory->save();

                // change state details inventario by inventario id
                $inventory_details = InventoryDetail::where('inventory_id', $id)->get();

                foreach ($inventory_details as $key => $value) {

                    // obtener material by id
                    $material = Material::find($value->material_id);

                    // obtener Event by code load
                    $event = Event::where('code', '=', 'LOAD')->first();

                    // si el evento es carga, restar al stock
                    if ($inventory->event_id == $event->id) {
                        $material->stock = $material->stock - $value->count;
                    } else {
                        $material->stock = $material->stock + $value->count;
                    }

                    $material->save();

                    $value->state_id = $stateActive->id;
                    $value->save();
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'El estado del registro se activó correctamente.',
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


    // EXPORTS EXCEL
    public function exportToExcel($id)
    {
        $date = Carbon::now();
        // concatener fecha y hora
        $date = $date->format('d-m-Y H:i:s');
        // get inventory data
        return Excel::download(new InventoryExport($id), 'Movimientos_' . $date . '.xlsx');
    }

}
