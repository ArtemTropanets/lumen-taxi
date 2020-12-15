<?php

namespace App\Http\Controllers;

use App\Models\Person;

class PersonController extends Controller
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

    public function getAll()
    {
        return [
            'persons' => Person::orderBy('name')
                ->get(),
        ];
    }

    public function create()
    {

    }
}
