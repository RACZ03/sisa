<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Technology;
use App\Models\Material;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\Event;
use App\Models\User;
use App\Models\Role;
use App\Models\State;

class ReportsController extends Controller
{
    public function index()
    {
        $state = STATE::where('code', '=', 'ACTIVE')->first();
        $role = ROLE::where('code', 'TECHNICAL')->first();

        $events = Event::where('state_id', $state->id)->get();
        $technicals = User::where('state_id', $state->id)->where('role_id', $role->id)->get();
        $technologies = Technology::where('state_id', $state->id)->get();

        return view('pages/reports/index', ['events' => $events, 'technicals' => $technicals, 'technologies' => $technologies]);
    }

    public function findData(Request $request)
    {
        try {

            $dateInit = $request->input('dateInit');
            $dateEnd = $request->input('dateEnd');
            $technologies = $request->input('technologies', []);
            $technical = $request->input('technical', []);
            $event = $request->input('event', []);

            // Consulta en el modelo Inventory utilizando los filtros
            $query = Inventory::query();

            // Filtrar por fechas si se proporcionaron
            if ($dateInit && $dateEnd) {
                $query->whereBetween('date', [$dateInit . ' 00:00:00', $dateEnd . ' 23:59:59']);

            }

            // Filtrar por tecnologías si se proporcionaron
            if (!empty($technologies) && !in_array('all', $technologies)) {
                $query->whereIn('technology_id', $technologies);
            }

            // Filtrar por técnicos si se proporcionaron
            if (!empty($technical) && !in_array('all', $technical)) {
                $query->whereIn('user_id', $technical);
            }

            // Filtrar por eventos si se proporcionaron
            if (!empty($event) && !in_array('all', $event)) {
                $query->whereIn('event_id', $event);
            }

            // validate inventory state ACTIVE
            $state = State::where('code', '=', 'ACTIVE')->first();
            $query->where('state_id', $state->id);
            // order by date asc
            $query->orderBy('date', 'asc');
            $inventories = $query->get();
            //dd($inventories); return text
            // $query_text = $query->toSql();
            // $query_text = str_replace('?', '"'.'%s'.'"', $query_text);
            // $query_text = vsprintf($query_text, $query->getBindings());

            // return response()->json(['status' => 200 , 'data' => $inventories, 'query' => $query_text]);
            // Obtener el detalle de cada inventario
            $all_details = [];
            $all_detail_series_delete = [];
            foreach ($inventories as $inventory) {

                $details = InventoryDetail::where('inventory_id', $inventory->id)
                                            ->where('state_id', $state->id)
                                            ->get();


                $seriesExisting = '';
                // create array series delete
                $seriesDelete = [];
                $seriesExistentes = [];
                $seriesNew = [];
                $count = 0;
                $idMaterial = 0;
                // recorrer details
                foreach ($details as $detail) {


                    $all_details[] = [
                        'id' => $inventory->id,
                        'code' => $inventory->code,
                        'date' => $inventory->date,
                        'event_id' => $inventory->event_id,
                        'event' => $inventory->event->name,
                        'technology_id' => $inventory->technology_id,
                        'technology' => $inventory->technology->name,
                        'route_id' => $inventory->route_id,
                        'route' => $inventory->route->name,
                        'user_id' => $inventory->user_id,
                        'user' => $inventory->user->name,
                        'creator_user_id' => $inventory->creator_user_id,
                        'creator_user' => $inventory->creator_user->name,
                        'state_id' => $inventory->state_id,
                        'state' => $inventory->state->name,
                        'detail_id' => $detail->id,
                        'detail_code' => $detail->code,
                        'detail_material_id' => $detail->material_id,
                        'detail_material' => $detail->material->name,
                        'detail_old_stock' => $detail->old_stock,
                        'detail_new_stock' => $detail->new_stock,
                        'detail_load' => $inventory->event_id == 1 ? $detail->count : 0,
                        'detail_debit' => $inventory->event_id == 2 ? $detail->count : 0,
                        'detail_series' => $detail->series,
                        'detail_series' => $detail->series,
                        // 'detail_series_existing' => $detail->existing_series,
                    ];



                }
                $seriesExisting = '';
                $seriesDelete = [];
                $seriesNew = [];

            }

            // Retornar los resultados como respuesta JSON con el detalle incluido
            return response()->json(['status' => 200 , 'data' => $all_details]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => 400 ], 400);
        }
    }
}
