<?php


namespace App\Models;


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
}
