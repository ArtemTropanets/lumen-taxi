<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'persons';

    protected $guarded = ['id'];


    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_person');
    }


    public static function getNoRouteMorningPersons()
    {
        return self::whereNotNull('morning_address')
            ->where('address_update_date', Carbon::today())
            ->whereDoesntHave('routes', function ($routes) {
                $routes->where('type', 'morning');
            })
            ->get();
    }


    public static function getNoRouteEveningPersons()
    {
        return self::whereNotNull('evening_address')
            ->where('address_update_date', Carbon::today())
            ->whereDoesntHave('routes', function ($routes) {
                $routes->where('type', 'evening');
            })
            ->get();
    }
}
