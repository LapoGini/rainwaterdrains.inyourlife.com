<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Street;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\User;

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

        $data = $request->all();
    
        if (empty($data['nuova_strada'])) {
            $ret['result']=false;
            $ret['error']="Strada mancante!";
            return response()->json([$ret], 200);
        }

        if (empty($data['comune_id'])) {
            $ret['result']=false;
            $ret['error']="Comune mancante!";
            return response()->json([$ret], 200);
        }

        $strada=Street::where('name',$data['nuova_strada'])->where('city_id',$data['comune_id'])->get();
        if ($strada->count()>0){
            $ret['result']=false;
            $ret['error']="Questa strada esiste già";
            return response()->json([$ret], 200);
        }

        $nuova_strada=Street::create([
            'name' => $data['nuova_strada'],
            'city_id' => $data['comune_id'],
        ]);

        return response()->json([
            'comuni' => (new CityController)->getAll()->original['data'],
            'vie' => (new CityController)->getViePerOgniComune($data['comune_id'])->original['data'],
            'result' => true,
            'error' => '',
            'codicevia' => $nuova_strada->id,
        ], 200);
    }

    private function checkUser($id_user,$iduserhash){
        $api_token=substr($iduserhash, 0, -1);
        $api_token=substr($api_token, 1);

        if (empty($id_user) || empty($api_token)){
            $ret['result']=false;
            $ret['error']='id_user and api_token necessari necessario in chiamata';
            $ret['api_token']=$api_token;
            return $ret;
            exit;
        }

        $user = User::where('api_token',$api_token)->find($id_user);

        if (empty($user)){
            $ret['result']=false;
            $ret['error']='utente non trovato o non autenticato';
            $ret['api_token']=$api_token;
            return $ret;
            exit;
        }

        $ret['result']=true;
        $ret['user']=$user;

        return $ret;
    }
}
