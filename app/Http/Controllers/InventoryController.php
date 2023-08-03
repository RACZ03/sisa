<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
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

class InventoryController extends Controller
{
    public function index()
    {
        // get states code ACTIVE
        $state = DB::table('states')->where('code', '=', 'ACTIVE')->first();
        // get inventory data
        $inventories = Inventory::where('state_id', $state->id)->get();
        return view('pages/inventory/index', ['inventories' => $inventories]);
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


        return view('pages/inventory/create',
            ['events' => $events, 'technologies' => $technologies, 'materials' => $materials, 'technicals' => $technicals, 'routes' => $routes]
        );
    }
}
