<?php

namespace App\Http\Controllers;


use App\Models\Person;
use App\Models\Route;
use App\Services\RouteService;
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
        return [
            'morning_routes' => Route::getTodayMorningRoutes(),
            'evening_routes' => Route::getTodayEveningRoutes(),
            'no_route_morning_persons' => Person::getNoRouteMorningPersons(),
            'no_route_evening_persons' => Person::getNoRouteEveningPersons(),
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
