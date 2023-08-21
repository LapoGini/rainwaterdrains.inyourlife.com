<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Street;
use Illuminate\Database\Eloquent\Builder;

use App\Utils\Functions;

class CityController extends Controller
{

    public function getAll() 
    {
        //$cities = City::with('user')->orderBy('id', 'DESC')->get();

        $cities= City::orderBy('id', 'DESC')->select('id as comune_id', 'name as comune_nome', 'district as provincia_id', 'pics as foto', 'user_id as CLIENTE', 'status as stato')->get();       
        return Functions::setResponse($cities, 'Città non trovate');
    }

    public function getViePerOgniComune($city_id) 
    {

        //dd($city_id);
        $streets = Street::where('city_id', $city_id)->get();
        return Functions::setResponse($streets, 'Strade non trovate');
    }
}