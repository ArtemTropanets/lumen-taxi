<?php


namespace App\Services;


use App\Models\Person;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RouteService
{
    public function saveRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            if (empty($route['persons'])) {
                continue;
            }

            Route::create([
                'type' => $route['type'],
                'scheduled_at' => $route['scheduled_at']
                    ?? (($route['type'] === 'evening') ? '18:10' : '08:30'),
            ])
                ->persons()->attach(
                    Arr::pluck($route['persons'], 'id')
                );
        }
    }


    public function getTodayRoutes(): Collection
    {
        $routes = Route::all();

        $persons_order_by_route_id = DB::table('route_person')
            ->get()
            ->groupBy('route_id');

        $today_address_persons_by_id = Person::where('address_update_date', Carbon::today())
            ->where(function ($query) {
                $query->whereNotNull('morning_address')
                    ->orWhereNotNull('evening_address');
            })
            ->get()
            ->keyBy('id');

        $routes->each(function ($route) use ($persons_order_by_route_id, $today_address_persons_by_id) {
            $route->persons = collect();

            $persons_order = $persons_order_by_route_id[$route->id] ?? [];

            foreach ($persons_order as $item) {
                $person = $today_address_persons_by_id[$item->person_id] ?? null;
                if (empty($person)) continue;

                if (!empty($person["{$route->type}_address"])) {
                    $route->persons->push($today_address_persons_by_id[$item->person_id]);
                }
            }
        });

        return $routes->reject(function ($route) {
            return $route->persons->isEmpty();
        })
            ->groupBy('type');
    }
}
