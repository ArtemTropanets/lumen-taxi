<?php

namespace App\Http\Controllers;


use App\Models\Person;
use App\Models\Route;
use App\Services\RouteService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $service;

    public function __construct()
    {
        $this->service = new RouteService();
    }

    public function getRoutesForEdit(): array
    {
        $route_person_pivot = DB::table('route_person')
            ->get();

        $today_address_persons = Person::where('address_update_date', Carbon::today())
            ->whereNotNull('morning_address')
            ->orWhereNotNull('evening_address')
            ->get();


        $routes = $this->service->getTodayRoutes($route_person_pivot, $today_address_persons);
        $no_route_persons = $this->service->getNoRoutePersons($route_person_pivot, $today_address_persons);

        return [
            'evening_routes' => $routes->get('evening') ?? collect(),
            'morning_routes' => $routes->get('morning') ?? collect(),
            'no_route_evening_persons' => $no_route_persons->get('evening'),
            'no_route_morning_persons' => $no_route_persons->get('morning'),
        ];
    }

    public function saveRoutes(Request $request): array
    {
        DB::statement("SET foreign_key_checks=0");
        Route::truncate();
        DB::table('route_person')->truncate();
        DB::statement("SET foreign_key_checks=1");

        $this->service->saveRoutes($request->evening_routes);
        $this->service->saveRoutes($request->morning_routes);

        return ['status' => 'ok'];
    }
}
