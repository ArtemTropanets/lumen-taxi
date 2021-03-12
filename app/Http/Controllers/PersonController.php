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

    public function getActive(): array
    {
        return [
            'persons' => Person::orderBy('name')->get(),
        ];
    }

    public function getAll()
    {
        return [
            'persons' => Person::withTrashed()->orderBy('name')->get(),
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
        $person = Person::withTrashed()->find($request->id);

        if (is_null($person)) {
            return ['status' => 'ok'];
        }

        $person->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'default_evening_address' => $request->default_evening_address,
                'default_morning_address' => $request->default_morning_address,
            ]);

        return ['status' => 'ok'];
    }

    public function deactivate(string $id): array
    {
        Person::destroy($id);

        return ['status' => 'ok'];
    }

    public function activate(Request $request): array
    {
        Person::withTrashed()->find($request->person_id)
            ->restore();

        return ['status' => 'ok'];
    }

    public function delete(string $id): array
    {
        Person::withTrashed()->find($id)
            ->forceDelete();

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
