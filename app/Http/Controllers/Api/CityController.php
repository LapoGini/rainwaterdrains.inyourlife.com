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
        $cities = City::with('user')->orderBy('id', 'DESC')->get();
        
        return Functions::setResponse($cities, 'Città non trovate');
    }
}
