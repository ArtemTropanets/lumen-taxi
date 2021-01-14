<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $guarded = ['id'];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'route_person');
    }

    public static function getTodayMorningRoutes()
    {
        return self::where('type', 'morning')
            ->with([
                'persons' => function ($persons) {
                    $persons->whereNotNull('morning_address')
                        ->where('address_update_date', Carbon::today());
                },
            ])
            ->whereHas('persons', function ($persons) {
                $persons->whereNotNull('morning_address')
                    ->where('address_update_date', Carbon::today());
            })
            ->get();
    }

    public static function getTodayEveningRoutes()
    {
        return self::where('type', 'evening')
            ->with([
                'persons' => function ($persons) {
                    $persons->whereNotNull('evening_address')
                        ->where('address_update_date', Carbon::today());
                },
            ])
            ->whereHas('persons', function ($persons) {
                $persons->whereNotNull('evening_address')
                    ->where('address_update_date', Carbon::today());
            })
            ->get();
    }
}
