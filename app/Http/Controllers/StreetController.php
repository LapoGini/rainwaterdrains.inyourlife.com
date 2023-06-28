<?php

namespace App\Http\Controllers;

use App\Models\Street;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StreetRequest;
use App\Models\City;


class StreetController extends Controller
{
    public function index() 
    {
        $streets = Street::with('city')->orderBy('id', 'DESC')->paginate(50);
        $cities = City::get();

        return view('pages.streets.index', compact('streets', 'cities'));
    }

    public function create()
    {
        $cities = City::get();

        return view('pages.streets.create', compact('cities'));
    }

    public function store(StreetRequest $request) : RedirectResponse
    {
        $validated = $request->validated();
        $city = City::find($validated['city_id']);
        if($city) {
            $street = Street::create($validated);
            $street->city()->associate($city)->save();
        }
        return to_route('streets.index');
    }

    public function edit(Street $street)
    {
        $cities = City::get();

        return view('pages.streets.edit', compact('street', 'cities'));
    }

    public function update(StreetRequest $request, Street $street) : RedirectResponse
    {
        //$this->authorize('update', $street);
        $validated = $request->validated();
        
        $city = City::find($validated['city_id']);
        if($city) {
            $street->city()->associate($city)->save();
        }
        $street->update($validated);

        return to_route('streets.index');
    }

    public function destroy(Street $street) : RedirectResponse
    {
        //$this->authorize('delete', $street);
        $street->delete();
        return to_route('streets.index');
    }
}
