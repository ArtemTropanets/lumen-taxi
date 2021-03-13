<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $guarded = ['id'];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'route_person');
    }
}
