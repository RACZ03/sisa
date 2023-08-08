<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\Technology;
use App\Models\Route;
use App\Models\State;
use App\Models\Inventory;
use App\Models\Event;

class HomeController extends Controller
{
    public function home()
    {
        // get state Active
        $state = State::where('code', 'ACTIVE')->first();

        // get users active
        $users = User::where('state_id', $state->id)->get();
        // get materials active
        $materials = Material::where('state_id', $state->id)->get();
        // get technologies active
        $technologies = Technology::where('state_id', $state->id)->get();
        // get routes active
        $routes = Route::where('state_id', $state->id)->get();
        $inventories = Inventory::where('state_id', $state->id)->get();
        // get inventories active
        $chartData = [];

    // Obtener el período de los últimos 6 meses desde hoy
        $currentDate = now();
        $sixMonthsAgo = $currentDate->copy()->subMonths(6);

        // Recorrer los meses en el período
        while ($currentDate >= $sixMonthsAgo) {
            $month = $currentDate->format('Y-m');

            // Filtrar inventarios por mes
            $inventoriesThisMonth = $inventories->filter(function ($inventory) use ($month) {
                return $inventory->created_at->format('Y-m') === $month;
            });

            // Obtener los IDs de eventos para este mes
            $eventIdsThisMonth = $inventoriesThisMonth->pluck('event_id');

            // Contar eventos por código en este mes
            $loadCount = Event::whereIn('id', $eventIdsThisMonth)->where('code', 'LOAD')->count();
            $debitCount = Event::whereIn('id', $eventIdsThisMonth)->where('code', 'DEBIT')->count();

            // Agregar datos al array
            $chartData[$month] = [
                'LOAD' => $loadCount,
                'DEBIT' => $debitCount,
            ];

            // Mover al mes anterior
            $currentDate->subMonth();
        }


        return view('dashboard', [
            'users' => $users,
            'materials' => $materials,
            'technologies' => $technologies,
            'routes' => $routes,
            'chartData' => $chartData,
        ]);
    }
}
