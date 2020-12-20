<?php

namespace App\Http\Controllers;


use App\Models\Person;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getRoutesForEdit()
    {
        return [
            'morning_routes' => Route::getMorningRoutes(),
            'evening_routes' => Route::getEveningRoutes(),
            'no_route_morning_persons' => Person::getNoRouteMorningPersons(),
            'no_route_evening_persons' => Person::getNoRouteEveningPersons(),
        ];
    }
}
