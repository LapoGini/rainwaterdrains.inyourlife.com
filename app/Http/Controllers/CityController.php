<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CityRequest;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;

class CityController extends Controller
{
    public function index() 
    {
        $cities = City::with('user')->orderBy('id', 'DESC')->paginate(50);

        $users = User::whereHas('roles', function (Builder $query) {
            $query->where('slug', '=', 'cliente');
        })->get();

        return view('pages.cities.index', compact('cities', 'users'));
    }

    public function store(CityRequest $request) : RedirectResponse
    {
        $validated = $request->validated();
        $user = User::find($validated['user_id']);
        if($user) {
            $city = City::create($validated);
            $city->user()->associate($user)->save();
        }
        return redirect(route('pages.cities.index'));
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

        return redirect(route('pages.cities.index'));
    }

    public function destroy(City $city) : RedirectResponse
    {
        //$this->authorize('delete', $city);
        $city->delete();
        return redirect(route('pages.cities.index'));
    }
}
