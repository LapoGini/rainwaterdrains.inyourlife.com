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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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

        if (empty($data['comune_id'])){
            $ret['result']=false;
            $ret['error']='Paramentri mancanti';
            return $ret;
        }

        $date = new \DateTime('now',new \DateTimeZone('Europe/Rome'));
        $cartellaDelGiorno='/'.$date->format('Ymd').'/';

        $comune=City::find($data['comune_id']);
        if (!empty($data['immagine'])) {
            $this->saveImage($data['immagine'],$data['caditoia_id'],$cartellaDelGiorno);
        }

        $cliente_comune=$comune->user()->first();    

        if ($data['recapito']=='') {
            $data['recapito']=1; //ID Fognatura Bianca
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

        if ($data['tipopozzetto']!=6) {  //se NON griglia
            $data['lunghezza']=0.5;
            $data['larghezza']=0.5;
            $data['profondita']=0.5;
        }

        //distinzione tra cliente UNIACQUE e APRICA
        switch ($cliente_comune->name) {
            case 'APRICA':
                if ($data['tipopozzetto']==6) {   //se griglia
                    //facciamo il calcolo
                    if ((int) $data['larghezza']>=25) {
                        $tmp= (double) $data['lunghezza'];
                        $tmp=$tmp*2;
                    } elseif ((int)$data['profondita'] < 30) {
                        $tmp= (double)$data['lunghezza'];
                        $tmp=$tmp/2;
                    } else{
                        $tmp= (double)$data['lunghezza'];
                        $tmp=$tmp*2;
                    }
                    $caditoie_equiv=$tmp;
                } else {
                    $caditoie_equiv="1";
                }
                break;
            case 'UNIACQUE':
                if ($data['tipopozzetto']==6) {   //se griglia
                    //facciamo il calcolo
                    $s=(double)$data['larghezza']*(double)$data['profondita'];
                    if ($s<=0.09) {
                        $caditoie_equiv=round($data['lunghezza']/2,1); // L/2 se BxH=S minore uguale di 0.09
                    } else {
                        $caditoie_equiv=round($data['lunghezza']/5,1); // L/5 se BxH=S maggiore di 0.09
                    }
                }
                break;
        }

        $data['tagsIds']=[$data['statocaditoia'],$data['tipopozzetto'],$data['recapito']];

        $timestamp_numero=explode('_',$data['caditoia_id'])[0];
        $data['time_stamp_pulizia']=date('Y-m-d H:i:s',$timestamp_numero);

        $street = Street::find($data['codice_via']);
        if($street) {
            $item = Item::make([
                'id_sd' => $data['id_sd'],
                'id_da_app' => $data['caditoia_id'],
                'time_stamp_pulizia' => $data['time_stamp_pulizia'],
                'civic' => $data['ubicazione'],
                'latitude' => $data['lat'],
                'longitude' => $data['lng'],
                'accuracy' => $data['tolleranza'],
                'altitude' => $data['altitude'],
                'height' => $data['lunghezza'],
                'width' => $data['larghezza'],
                'depth' => $data['profondita'],
                'pic' => $data['caditoia_id'].'.jpg',
                'note' => $data['note'],
                /*'street_id' => $street->id,
                'user_id' => $user->id,*/
                'note' => $data['note'],
                'caditoie_equiv' => $caditoie_equiv
            ]);
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

    /**
     * ritorna caditoie fotografate per una determinata data
     * @param Request la richiesta con data (Y-m-d) per filtrare dati 
     * @return json dati di tutte le caditoie filtrate
     */
    public function getCaditoieScansionate (Request $request){
        $giorno=$request->giorno;
        if (empty($giorno)){
            $date = new \DateTime('now',new \DateTimeZone('Europe/Rome'));
            $giorno=$date->format('Y-m-d');
        }
        $items = Item::with('street', 'street.city', 'tags', 'user')->whereRaw('DATE_FORMAT(time_stamp_pulizia, "%Y-%m-%d")="'.$giorno.'"')->get();

        $caditoie=[];
        $row=0;
        $aggregato['Griglia']=$aggregato['Griglia']=$aggregato['Griglia']=0;
        foreach ($items as $i){
            $itemTags = $i->tags;
            foreach ($itemTags as $tag){
                if ($tag->type=='Stato'){
                    $caditoie[$row]['stato_nome']=$tag->name;
                } else if ($tag->type=='Recapito'){
                    $caditoie[$row]['recapito_nome']=$tag->name;
                } else if ($tag->type=='Tipo Pozzetto'){
                    $caditoie[$row]['pozzetto_nome']=$tag->name;
                }
            }
            $caditoie[$row]['data_caditoia']=$i->time_stamp_pulizia;
            $caditoie[$row]['ubicazione']=$i->civic;
            $caditoie[$row]['strada_nome']=$i->street->name;
            $caditoie[$row]['caditoie_lat']=$i->latitude;
            $caditoie[$row]['caditoie_lng']=$i->longitude;
            $caditoie[$row]['caditoie_altitude']=$i->altitude;
            $caditoie[$row]['foto_id']=env('APP_URL') . '/img_items/' . date('Ymd', strtotime($i->time_stamp_pulizia)) . '/' . $i->pic;
            $caditoie[$row]['caditoie_note']=$i->note;
            $aggregato[$caditoie[$row]['pozzetto_nome']]++;
            $row++;
        }

        $ret['result']=true;
        $ret['cadiotie']=$caditoie;
        $ret['aggregato'] = $aggregato;
        return response()->json($ret, 200);     
    }

    /**
     * ritorna caditoie fotografate per una determinata data ed una determinata via
     * @param Request la richiesta con giorniindietro e codicevia per filtrare i dati
     * @return json dati di tutte le caditoie filtrate
     */
    public function getCaditoieScansionatePerVia (Request $request){
        $giorniindietro=$request->giorniindietro;
        $codicevia=$request->codice_via;
        if (empty($giorniindietro)){
            $giorniindietro=7;
        }

        if (empty($codicevia)){
            $ret['result'] = false;
            $ret['error'] = "Parametro strada mancante";
            return response()->json($ret, 200);
        }

        $items = Item::with('street', 'street.city', 'tags', 'user')->whereRaw('street_id='.$codicevia.' AND DATE_FORMAT(time_stamp_pulizia, "%Y-%m-%d")>="'.Carbon::now()->subDays($giorniindietro).'"')->get();

        $caditoie=[];
<<<<<<< HEAD
        $row=0;
=======
        $row=0; 
>>>>>>> 3733e0b6b90c12247cd8bac0fd5ba1691da5ce60
        foreach ($items as $i){
            $itemTags = $i->tags;
            foreach ($itemTags as $tag){
                if ($tag->type=='Stato'){
                    $caditoie[$row]['stato_nome']=$tag->name;
                } else if ($tag->type=='Recapito'){
                    $caditoie[$row]['recapito_nome']=$tag->name;
                } else if ($tag->type=='Tipo Pozzetto'){
                    $caditoie[$row]['pozzetto_nome']=$tag->name;
                }
            }
            $caditoie[$row]['time_stamp_pulizia']=$i->time_stamp_pulizia;
            $caditoie[$row]['ubicazione']=$i->civic;
            $caditoie[$row]['strada_nome']=$i->street->name;
            $caditoie[$row]['caditoie_lat']=$i->latitude;
            $caditoie[$row]['caditoie_lng']=$i->longitude;
            $caditoie[$row]['caditoie_altitude']=$i->altitude;
            $caditoie[$row]['foto_id']=env('APP_URL') . '/img_items/' . date('Ymd', strtotime($i->time_stamp_pulizia)) . '/' . $i->pic;
            $caditoie[$row]['caditoie_note']=$i->note;
            $row++;
        }

        $ret['result']=true;
        $ret['cadiotie']=$caditoie;
        return response()->json($ret, 200);
        
<<<<<<< HEAD
=======
        
>>>>>>> 3733e0b6b90c12247cd8bac0fd5ba1691da5ce60
    }
}
