<?php


namespace App\Services;


use App\Models\Route;
use Illuminate\Support\Arr;

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
                'scheduled_at' => ($route['scheduled_at'])
                    ? $route['scheduled_at']
                    : (
                    ($route['type'] === 'evening') ? '18:10' : '08:30'
                    ),
            ])
                ->persons()->attach(
                    Arr::pluck($route['persons'], 'id')
                );
        }
    }
}
