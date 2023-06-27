<?php

namespace App\Http\Controllers;

use App\Models\Street;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StreetRequest;
use App\Models\City;
use Inertia\Inertia;


class StreetController extends Controller
{
    public function index() 
    {
        $streets = Street::with('city')->orderBy('id', 'DESC')->paginate(50);
        $cities = City::get();

        return view('pages.streets.index', compact('streets', 'cities'));
    }

    public function store(StreetRequest $request) : RedirectResponse
    {
        $validated = $request->validated();
        $city = City::find($validated['city_id']);
        if($city) {
            $street = Street::create($validated);
            $street->city()->associate($city)->save();
        }
        return redirect(route('pages.streets.index'));
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

        return redirect(route('pages.streets.index'));
    }

    public function destroy(Street $street) : RedirectResponse
    {
        //$this->authorize('delete', $street);
        $street->delete();
        return redirect(route('pages.streets.index'));
    }

    
}
