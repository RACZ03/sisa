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

    // controller store inventory
    public function store(Request $request)
    {

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
                $inventory_detail->old_stock = $material->stock;
                // si el evento es carga, sumar al new_stock
                if ($request->input('event_id') == $event->id) {
                    $inventory_detail->new_stock = $material->stock + $value['count'];
                } else {
                    $inventory_detail->new_stock = $material->stock - $value['count'];
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

            return response()->json(['message' => 'El inventario se guardó correctamente.', 'status' => 200], 201);
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
        return Excel::download(new InventoryExport($id), 'inventario_' . $date . '.xlsx');
    }

}
