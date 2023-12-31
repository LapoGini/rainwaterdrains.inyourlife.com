<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Street;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;


use App\Utils\Functions;

class StreetController extends Controller
{

    public function getAll() 
    {
        $streets = Street::orderBy('name')
            ->get();
    
        return Functions::setResponse($streets, 'Strade non trovate');
    }
    

    public function getAllByCityId()
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

    public function setVia(Request $request)
    {

        $data = $request->all();

        Log::info('Received data:', $data);


        if (empty($data['name'])) {
            return response()->json(['result' => false, 'error' => "Strada mancante!"], 200);
        }
    
        if (empty($data['city_id'])) {
            return response()->json(['result' => false, 'error' => "Comune mancante!"], 200);
        }
    
        return DB::transaction(function () use ($data) {
            $strada = Street::where('name', $data['name'])
                            ->where('city_id', $data['city_id'])
                            ->lockForUpdate()  // Lock the selected rows
                            ->first();

            if ($strada) {
                // Se la strada esiste già, aggiorniamo solo il valore di street_id_app
                $strada->street_id_app = $data['id'];
                $strada->save();
            } else {
                // Se la strada non esiste, la creiamo
                $strada = Street::create([
                    'name' => $data['name'],
                    'city_id' => $data['city_id'],
                    'street_id_app' => $data['id'],  // Vecchio id da App
                ]);
            }
    
            return response()->json([
                'idOld' => $data['id'],
                'idNew' => $strada->id,
                'comuni' => (new CityController)->getAll()->original['data'],
                'vie' => (new CityController)->getViePerOgniComune($data['city_id'])->original['data'],
                'result' => true,
                'error' => '',
            ], 200);
        }, 5);
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
