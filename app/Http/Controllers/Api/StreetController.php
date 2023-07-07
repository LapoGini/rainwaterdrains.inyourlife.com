<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Street;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use App\Utils\Functions;

class StreetController extends Controller
{

    public function getAll() 
    {
        $streets = Street::with('city')->select('*','id as codice_via','name as strada_nome','city_id as comune_id')->orderBy('id', 'DESC')->get();
        
        return Functions::setResponse($streets, 'Strade non trovate');
    }

    public function getByCityId(int $city_id) 
    {
        /*$streets = Street::with('city')->whereHas('city', function (Builder $query) use ($city_id) {
            $query->where('id', '=', $city_id);
        })->get();*/
        $streets = Street::where('city_id', $city_id)->select('id as codice_via','name as strada_nome')->get();
        return Functions::setResponse($streets, 'Strade non trovate');
    }

    public function setVia(Request $request){
        if (empty($request->nuova_strada)) {
            $ret['result']=false;
            $ret['error']="Strada mancante!";
            return response()->json([$ret], 200);
        }
    
        if (empty($request->comune_id)) {
            $ret['result']=false;
            $ret['error']="Comune mancante!";
            return response()->json([$ret], 200);
        }

        $strada=Street::where('name',$request->nuova_strada)->get();
        if ($strada->count()>0){
            $ret['result']=false;
            $ret['error']="Questa strada esiste già";
            return response()->json([$ret], 200);
        }

        $nuova_strada=Street::create([
            'name' => $request->nuova_strada,
            'city_id' => $request->comune_id,
        ]);

        return response()->json([
            'comuni' => (new CityController)->getAll()->original['data'], 
            'vie' => (new CityController)->getViePerOgniComune()->original['data'],
            'result' => true,
            'error' => '',
            'codicevia' => $nuova_strada->id,
        ], 200);
    }
}
