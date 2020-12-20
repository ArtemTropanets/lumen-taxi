<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public function persons()
    {
        return $this->belongsToMany(Person::class, 'route_person');
    }

    public static function getMorningRoutes()
    {
        return self::where('is_morning', 1)
            ->with('persons')
            ->whereDate('created_at', Carbon::today())
            ->get();
    }

    public static function getEveningRoutes()
    {
        return self::where('is_evening', 1)
            ->with('persons')
            ->whereDate('created_at', Carbon::today())
            ->get();
    }
}
