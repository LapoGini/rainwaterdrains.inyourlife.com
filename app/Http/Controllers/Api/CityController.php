<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
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

    public function getViePerOgniComune() 
    {
        $vie=[];
        $cities= City::orderBy('id', 'DESC')->get();
        foreach ($cities as $comune){
            $vie[$comune->id]= (new StreetController)->getByCityId($comune->id)->original['data'];
        }        
        return Functions::setResponse($vie, 'Vie non trovate');
    }
}