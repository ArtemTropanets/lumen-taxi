<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function saveTodayAddresses(Request $request)
    {
        Person::where('id', $request->person_id)
            ->update([
                'morning_address' => $request->morning_address ? $request->morning_address : null,
                'evening_address' => $request->evening_address ? $request->evening_address : null,
                'address_update_date' => Carbon::now(),
            ]);

        return ['status' => 'success'];
    }
}
