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

    public function create(Request $request): array
    {
        Person::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'default_evening_address' => $request->default_evening_address,
            'default_morning_address' => $request->default_morning_address,
        ]);

        return ['status' => 'ok'];
    }

    public function edit(Request $request): array
    {
        Person::find($request->id)
            ->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'default_evening_address' => $request->default_evening_address,
                'default_morning_address' => $request->default_morning_address,
            ]);

        return ['status' => 'ok'];
    }

    public function softDelete(string $id): array
    {
        Person::destroy($id);

        return ['status' => 'ok'];
    }

    public function restore(Request $request): array
    {
        Person::find($request->person_id)
            ->restore();

        return ['status' => 'ok'];
    }

    public function saveTodayAddresses(Request $request): array
    {
        Person::where('id', $request->person_id)
            ->update([
                'morning_address' => $request->morning_address ? $request->morning_address : null,
                'evening_address' => $request->evening_address ? $request->evening_address : null,
                'address_update_date' => Carbon::now(),
            ]);

        return ['status' => 'ok'];
    }
}
