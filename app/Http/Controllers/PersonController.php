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

    public function getAll(): array
    {
        return [
            'persons' => Person::orderBy('name')
                ->get(),
        ];
    }

    public function create(Request $request)
    {
        Person::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'evening_default_address' => $request->evening_default_address,
            'morning_default_address' => $request->morning_default_address,
        ]);

        return ['status' => 'success'];
    }

    public function saveTodayAddresses(Request $request): array
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
