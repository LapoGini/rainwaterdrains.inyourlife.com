<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Street;
use Illuminate\Database\Eloquent\Builder;

use App\Utils\Functions;

class StreetController extends Controller
{

    public function getAll() 
    {
        $streets = Street::with('city')->orderBy('id', 'DESC')->get();
        
        return Functions::setResponse($streets, 'Strade non trovate');
    }

    public function getByCityId(int $city_id) 
    {
        $streets = Street::with('city')->whereHas('city', function (Builder $query) use ($city_id) {
            $query->where('id', '=', $city_id);
        })->get();
        
        return Functions::setResponse($streets, 'Strade non trovate');
    }
}
