<?php


namespace App\Services;


use App\Models\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

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


    public function getTodayRoutes(Collection $route_person_pivot, Collection $today_address_persons): Collection
    {
        $routes = Route::all();

        $persons_order_by_route_id = $route_person_pivot->groupBy('route_id');
        $today_address_persons_by_id = $today_address_persons->keyBy('id');

        $routes->each(function ($route) use ($persons_order_by_route_id, $today_address_persons_by_id) {
            $route->persons = collect();

            foreach ($persons_order_by_route_id[$route->id] as $persons_order) {
                $route->persons->push($today_address_persons_by_id[$persons_order->person_id]);
            }
        });

        return $routes->groupBy('type');
    }


    public function getNoRoutePersons(Collection $route_person_pivot, Collection $today_address_persons): Collection
    {
        $persons_ids_having_route_assoc = $route_person_pivot->keyBy('person_id');

        $no_route_persons = collect([
            'evening' => collect(),
            'morning' => collect(),
        ]);

        $today_address_persons->each(function ($person) use ($no_route_persons, $persons_ids_having_route_assoc) {
            if (isset($persons_ids_having_route_assoc[$person->id])) return;

            foreach ($no_route_persons as $type => $collection) {
                if (!empty($person["{$type}_address"])) {
                    $collection->push($person);
                }
            }
        });

        return $no_route_persons;
    }
}
