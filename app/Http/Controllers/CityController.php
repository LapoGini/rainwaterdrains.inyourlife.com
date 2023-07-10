<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CityRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;

class CityController extends Controller
{
    public function index() 
    {
        $cities = City::with('user')->orderBy('id', 'DESC')->paginate(50);

        $users = User::whereHas('roles', function (Builder $query) {
            $query->where('slug', '=', 'cliente');
        })->get();

        return view('pages.Cities.index', compact('cities', 'users'));
    }

    public function create()
    {
        $districts = Config::get('districts');
        $users = User::whereHas('roles', function (Builder $query) {
            $query->where('slug', '=', 'cliente');
        })->get();

        return view('pages.Cities.create', compact('districts', 'users'));
    }

    public function store(CityRequest $request) : RedirectResponse
    {       
        $validated = $request->validated();
        $user = User::find($request['user_id']);
        //dd($validated);
        if($user) {
            $city = City::create($validated);
            $city->user()->associate($user)->save();
        }

        return to_route('cities.index');
    }

    public function edit(City $city)
    {
        $districts = Config::get('districts');
        $users = User::whereHas('roles', function (Builder $query) {
            $query->where('slug', '=', 'cliente');
        })->get();

        return view('pages.Cities.edit', compact('city', 'districts', 'users'));
    }

    public function update(CityRequest $request, City $city) : RedirectResponse
    {
        //$this->authorize('update', $city);
        $validated = $request->validated();
        
        $user = User::find($validated['user_id']);
        if($user) {
            $city->user()->associate($user)->save();
        }
        $city->update($validated);

        return to_route('cities.index');
    }

    public function destroy(City $city) : RedirectResponse
    {
        $city->delete();
        return to_route('cities.index');
    }
}
