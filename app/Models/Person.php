<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_person');
    }

    public static function getNoRouteMorningPersons()
    {
        return self::whereNotNull('morning_address')
            ->where('address_update_date', Carbon::today())
            ->whereDoesntHave('routes', function ($routes) {
                $routes->where('is_morning', 1);
            })
            ->get();
    }

    public static function getNoRouteEveningPersons()
    {
        return self::whereNotNull('evening_address')
            ->where('address_update_date', Carbon::today())
            ->whereDoesntHave('routes', function ($routes) {
                $routes->where('is_evening', 1);
            })
            ->get();
    }
}
