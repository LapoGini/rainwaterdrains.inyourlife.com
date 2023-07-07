<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Item;
use App\Models\Street;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Utils\Functions;

use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    public function getAll()
    {
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->get();

        return Functions::setResponse($items, 'Operazioni non trovate');
    }

    public function getByCityId(int $city_id)
    {
        $items = Street::with('street', 'street.city', 'tags', 'user')->whereHas('street.city', function (Builder $query) use ($city_id) {
            $query->where('id', '=', $city_id);
        })->get();

        return Functions::setResponse($items, 'Strade non trovate');
    }

    public function getByStreetId(int $street_id)
    {
        $items = Street::with('street', 'street.city', 'tags', 'user')->whereHas('street', function (Builder $query) use ($street_id) {
            $query->where('id', '=', $street_id);
        })->get();

        return Functions::setResponse($items, 'Strade non trovate');
    }

    public function getByUserId(int $user_id)
    {
        $items = Street::with('street', 'street.city', 'tags', 'user')->whereHas('user', function (Builder $query) use ($user_id) {
            $query->where('id', '=', $user_id);
        })->get();

        return Functions::setResponse($items, 'Strade non trovate');
    }

    public function getByTagId(int $tag_id)
    {
        $items = Street::with('street', 'street.city', 'tags', 'user')->whereHas('tag', function (Builder $query) use ($tag_id) {
            $query->where('id', '=', $tag_id);
        })->get();

        return Functions::setResponse($items, 'Strade non trovate');
    }

    public function set(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = $request->all();

        $validator = Validator::make($data,[
            'latitude'=> 'required|numeric',
            'longitude'=> 'required|numeric',
            'altitude'=> 'required|numeric',
            'accuracy'=> 'required|numeric',
            'pic' => 'string|nullable',
            'note' => 'string|nullable',
            'height'=> 'required|numeric',
            'width'=> 'required|numeric',
            'depth'=> 'required|numeric',
            'street_id'=> 'required|numeric',
            'tagsIds' => 'array'
        ]);

        if($validator->fails()){
            return response()->json(['validation_errors' => $validator->messages()],201);
        }


        $street = Street::find($data['street_id']);

        if($street) {
            $item = Item::make($data);
            $item->street()->associate($street);
            $item->user()->associate($user);
            $item->save();
        }
        if(isset($data['tagsIds'])){
            $item->tags()->sync($data['tagsIds']);
        }

        return response()->json(['success' => $item], 200);
    }

    /**
     * ritorna id item aggiornato con data deleted a NOW()
     *
     * @param int $id Item da aggiornare
     * @return boolean true or false
     */
    public function setDeleted(int $id)
    {
        $item = Item::find($id);
        $result=false;
        if ($item->cancellabile && empty($item->deleted_at)) {
            $date = new \DateTime('now',new \DateTimeZone('Europe/Rome'));
            $item->deleted_at= $date->format('Y-m-d H:i:s');
            $result=$item->save();
        }
        return $result;
    }

    /**
     * ritorna elenco id item da cancellare su una determinata scheda SD che viene passata in richiesta
     * e con valore deleted_at==NULL
     *
     * @param string $id_SD Item da aggiornare
     * @return string|false elenco id separati da virgola
     */
    public function getCancellabili(string $id_SD)
    {
        $items = Item::where('id_sd',$id_SD)->whereNotNull('cancellabile')->whereNull('deleted_at')->get();
        $list=[];
        foreach ($items as $i){
            $list[]=$i->id;
        }

        if (!empty($list)){
            return implode(",",$list);
        } else return false;
    }

    /**
     * salvataggio caditoia inviata da telefono al server
     * @param Request la richiesta con tutti i dati inviati da app
     * @return int|false id item inserito sul server oppure false se errore
     */
    public function setCaditoia(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = $request->all();

        $date = new \DateTime('now',new \DateTimeZone('Europe/Rome'));
        $cartellaDelGiorno='/'.$date->format('Ymd').'/';

        $comune=City::find($data['comune_id']);
        if (empty($data['immagine']) && $comune->pics) {
            $this->saveImage($data['immagine'],$data['caditoia_id'],$cartellaDelGiorno);
        }

        if ($data['recapito']=='') {
            $data['recapito']=1;
        }
        if ($data['lunghezza']=='') {
            $data['lunghezza']=0.5;
        }
        if ($data['larghezza']=='') {
            $data['larghezza']=0.5;
        } else {
            $data['larghezza']/=100;

        }
        if ($data['profondita']=='') {
            $data['profondita']=0.5;
        } else {
            $data['profondita']/=100;
        }

        if ($data['tipopozzetto']!=4) {  //se griglia
            $data['lunghezza']=0.5;
            $data['larghezza']=0.5;
            $data['profondita']=0.5;
        }

        if ($data['tipopozzetto']==4) {   //se griglia
            //facciamo il calcolo
            if ((int) $data['larghezza']>=25) {
                $tmp= (double) $data['lunghezza'];
                $tmp=$tmp*2;
            } elseif ((int) $data['profondita'] < 30) {
                $tmp= (double) $data['lunghezza'];
                $tmp=$tmp/2;
            } else{
                $tmp= (double) $data['lunghezza'];
                $tmp=$tmp*2;
            }
            $caditoie_equiv=(string) $tmp;

        } else {
            $caditoie_equiv="1";
        }

        /*caditoie_id,
        stato_id,
        caditoie_lat,
        caditoie_lng,
        caditoie_altitude,
        codice_via,
        comune_id,
        caditoie_ubicazione,
        tipo_pozzetto_id,
        recapito,
        larghezza,
        lunghezza,
        profondita,
        foto_id,
        caditoie_note,
        user_id,
        caditoie_timestamp,
        stato_custom,
        pozzetto_custom,
        caditoie_equiv*/

        /* $_GET['caditoia_id'],
        $_GET['statocaditoia'],
        $_GET['lat'],
        $_GET['lng'],
        $_GET['altitude'],
        $_GET['codice_via'],
        $_GET['comune_id'],
        $_GET['ubicazione'],
        $_GET['tipopozzetto'],
        $_GET['recapito'],
        $_GET['larghezza'],
        $_GET['lunghezza'],
        $_GET['profondita'],
        $FOTO['foto_id'],
        $_GET['note'],
        $user['user_id'],
        $now,
        '17' => isset($_GET['stato_custom']) ? $_GET['stato_custom'] : null,
        '18' => isset($_GET['pozzetto_custom']) ? $_GET['pozzetto_custom'] : null,
        $caditoie_equiv*/



        $street = Street::find($data['codice_via']);

        if($street) {
            $item = Item::create($data);
            $item->street()->associate($street);
            $item->user()->associate($user);
            $item->save();
        }
        if(isset($data['tagsIds'])){
            $item->tags()->sync($data['tagsIds']);
        }

        $ret['result']=true;
        $ret['id']=$item->id;

        return $ret;

    }

    private function saveImage($imagedata,$imageName,$cartellaDelGiorno){
        if(!Storage::disk('img_items')->exists($cartellaDelGiorno)) {
            Storage::disk('img_items')->makeDirectory($cartellaDelGiorno, 0775, true);
        }

        $imagedata = str_replace('data:image/jpg;base64,', '', $imagedata);
        $imagedata = str_replace(' ', '+', $imagedata);

        if(Storage::disk('img_items')->exists($cartellaDelGiorno.$imageName.'.jpg')) {
            Storage::disk('img_items')->put($cartellaDelGiorno.$imageName.'_'.date('His').'.jpg', base64_decode($imagedata));
        } else {
            Storage::disk('img_items')->put($cartellaDelGiorno.$imageName.'.jpg', base64_decode($imagedata));
        }
    }
}
