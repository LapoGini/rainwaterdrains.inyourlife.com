<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Item;
use App\Models\Street;
use App\Models\User;
use App\Models\ItemTag;
use App\Models\Tag;
use App\Models\TagType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


use App\Utils\Functions;
use Exception;
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

    // INIZIO GEO.ZA //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function set(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = $request->all();

        Log::info('Received data:', $data);


        // Validator per accettare i dati dalla vecchia app
        $validator = Validator::make($data, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'required|numeric',
            'accuracy' => 'required|numeric',
            'pic' => 'string|nullable',
            'note' => 'string|nullable',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'depth' => 'nullable|numeric',
            'street_id' => 'required|numeric',
            'tags' => 'string' // 'tags' deve essere una stringa JSON
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->messages()], 201);
        }

        $street = Street::where(function ($query) use ($data) {
            $query->where('street_id_app', $data['street_id'])
                ->orWhere('id', $data['street_id']);
        })->first();

        if (isset($street)) {
            $item = Item::make($data);
            $item->street_id = $street->id;
            $item->user_id = $user->id;
            $item->save();
        }

        if (isset($data['tags'])) {
            $tagsArray = json_decode($data['tags'], true); // Decodifica la stringa JSON in un array

            $tagData = ['item_id' => $item->id];

            foreach ($tagsArray as $tagTypeId => $tagId) {
                $tagType = TagType::find($tagTypeId);
                if ($tagType) {
                    $columnName = strtolower($tagType->name) . '_tag_id';
                    $tagData[$columnName] = $tagId;
                }
            }
            ItemTag::create($tagData);
        }

        return response()->json(['success' => $item], 200);
    }
    // FINE GEO.ZA //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    /*
    INIZIO RWD
    public function set(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required|numeric|exists:users,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'required|numeric',
            'accuracy' => 'required|numeric',
            'pic' => 'string|nullable',
            'note' => 'string|nullable',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'depth' => 'nullable|numeric',
            'street_id' => 'required|numeric',
            'civico' => 'string|nullable',
            'time_stamp_pulizia' => 'required|date_format:Y-m-d H:i:s',
            'id_da_app' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['validation_errors' => $validator->messages()],201);
        }

        $street = Street::where(function($query) use ($data) {
            $query->where('street_id_app', $data['street_id'])
                  ->orWhere('id', $data['street_id']);
        })->first();

        if (isset($street)) {
            $item = Item::make($data);
            $item->street_id = $street->id;
            $item->user_id = $data['user_id'];
            $item->save();
        }

        if($data['tags'] !== null) {
            $tags = json_decode($data['tags'], true);

            $tagData = ['item_id' => $item->id];

            foreach($tags as $tagId => $tagValue) {
                $tagType = TagType::find($tagId);

                if($tagType) {
                    $columnName = $tagType->name . '_tag_id';
                    $columnName = strtolower($columnName); 

                    $tagData[$columnName] = $tagValue;
                }
            }
            ItemTag::create($tagData);
        }


        return response()->json(['success' => $item], 200);
    }
    FINE RWD
    */

    /**
     * ritorna id item aggiornato con data deleted a NOW()
     *
     * @param Request $request caditoia_id, id_user, iduserhash Item da aggiornare
     * @return boolean true or false
     */
    public function setDeleted(Request $request)
    {
        $data = $request->all()['data'];
        $check = $this->checkUser($data['id_user'], $data['iduserhash']);

        if ($check['result']) {
            $user = $check['user'];
        } else {
            $ret['result'] = false;
            $ret['error'] = $check;
            return response()->json($ret, 200);
        }

        $item = Item::where('id_da_app', $data['caditoia_id'])->first();
        if ($item->cancellabile && empty($item->deleted_at)) {
            $date = new \DateTime('now', new \DateTimeZone('Europe/Rome'));
            $item->deleted_at = $date->format('Y-m-d H:i:s');
            $result = $item->save();
        }
        $ret['result'] = true;
        return response()->json($ret, 200);
    }

    /**
     * 
     * ritorna elenco id item da cancellare su una determinata scheda SD che viene passata in richiesta
     * e con valore deleted_at==NULL
     *
     * @param Request $request da prendere is_user, iduserhash e id_sd Item da aggiornare
     * @return string|false elenco id separati da virgola
     */
    //SENZA SD
    public function getCancellabili(Request $request)
    {
        $data = $request->all()['data'];

        $check = $this->checkUser($data['id_user'], $data['iduserhash']);

        if ($check['result']) {
            $user = $check['user'];
        } else {
            return response()->json(['result' => false, 'error' => $check], 200);
        }

        // Aggiornamento della query: ora recupera tutti gli elementi che possono essere cancellati
        $items = Item::whereNotNull('cancellabile')->whereNull('deleted_at')->get();

        $list = [];
        foreach ($items as $i) {
            $list[] = $i->id_da_app;
        }

        return response()->json(['result' => true, 'cancellabili' => $list], 200);
    }


    /**
     * salvataggio caditoia inviata da telefono al server
     * @param Request la richiesta con tutti i dati inviati da app
     * @return int|false id item inserito sul server oppure false se errore
     */
    public function setCaditoia(Request $request)
    {
        //$user = Auth::guard('api')->user();
        $data = $request->all()['data'];

        if (!isset($data['iduserhash'])) $data['iduserhash'] = '';
        if (!isset($data['id_user'])) $data['id_user'] = 0;
        if (!isset($data['tipopozzetto'])) $data['tipopozzetto'] = 0;
        if (!isset($data['lunghezza'])) $data['lunghezza'] = '';
        if (!isset($data['larghezza'])) $data['larghezza'] = '';
        if (!isset($data['profondita'])) $data['profondita'] = '';
        if (!isset($data['recapito'])) $data['recapito'] = 0;
        if (!isset($data['ubicazione'])) $data['ubicazione'] = '';
        if (!isset($data['statocaditoia'])) $data['statocaditoia'] = 0;
        if (!isset($data['note'])) $data['note'] = '';
        if (!isset($data['caditoia_id'])) $data['caditoia_id'] = 0;
        if (!isset($data['lat'])) $data['lat'] = '';
        if (!isset($data['lng'])) $data['lng'] = '';
        if (!isset($data['altitude'])) $data['altitude'] = 0;
        if (!isset($data['tolleranza'])) $data['tolleranza'] = 0;
        if (!isset($data['comune_id'])) $data['comune_id'] = 0;
        if (!isset($data['codice_via'])) $data['codice_via'] = 0;
        if (!isset($data['immagine'])) $data['immagine'] = '';

        $api_token = substr($data['iduserhash'], 0, -1);
        $api_token = substr($api_token, 1);

        if (empty($data['comune_id'])) {
            $ret['result'] = false;
            $ret['error'] = 'Paramentri mancanti';
            $ret['request'] = $data;
            return $ret;
        }

        if (empty($data['id_user']) || empty($api_token)) {
            $ret['result'] = false;
            $ret['error'] = 'id_user and api_token necessari necessario in chiamata';
            $ret['request'] = $data;
            $ret['api_token'] = $api_token;
            return $ret;
        }

        $user = User::where('api_token', $api_token)->find($data['id_user']);

        if (empty($user)) {
            $ret['result'] = false;
            $ret['error'] = 'utente non trovato o non autenticato';
            $ret['request'] = $data;
            $ret['api_token'] = $api_token;
            return $ret;
        }

        $date = new \DateTime('now', new \DateTimeZone('Europe/Rome'));
        $cartellaDelGiorno = '/' . $date->format('Ymd') . '/';

        $comune = City::find($data['comune_id']);
        if (!empty($data['immagine'])) {
            $this->saveImage($data['immagine'], $data['caditoia_id'], $cartellaDelGiorno);
        }

        $cliente_comune = $comune->user()->first();

        if ($data['recapito'] == '') {
            $data['recapito'] = 1; //ID Fognatura Bianca
        }
        if ($data['lunghezza'] == '') {
            $data['lunghezza'] = 0.5;
        }
        if ($data['larghezza'] == '') {
            $data['larghezza'] = 0.5;
        } else {
            $data['larghezza'] /= 100;
        }
        if ($data['profondita'] == '') {
            $data['profondita'] = 0.5;
        } else {
            $data['profondita'] /= 100;
        }

        if ($data['tipopozzetto'] != 6) {  //se NON griglia
            $data['lunghezza'] = 0.5;
            $data['larghezza'] = 0.5;
            $data['profondita'] = 0.5;
        }
        $caditoie_equiv = 0;

        //distinzione tra cliente UNIACQUE e APRICA
        switch ($cliente_comune->name) {
            case 'APRICA':
                if ($data['tipopozzetto'] == 6) {   //se griglia
                    //facciamo il calcolo
                    if ((int) $data['larghezza'] >= 25) {
                        $tmp = (float) $data['lunghezza'];
                        $tmp = $tmp * 2;
                    } elseif ((int)$data['profondita'] < 30) {
                        $tmp = (float)$data['lunghezza'];
                        $tmp = $tmp / 2;
                    } else {
                        $tmp = (float)$data['lunghezza'];
                        $tmp = $tmp * 2;
                    }
                    $caditoie_equiv = $tmp;
                } else {
                    $caditoie_equiv = "1";
                }
                break;
            case 'UNIACQUE':
                if ($data['tipopozzetto'] == 6) {   //se griglia
                    //facciamo il calcolo
                    $s = (float)$data['larghezza'] * (float)$data['profondita'];
                    if ($s <= 0.09) {
                        $caditoie_equiv = round($data['lunghezza'] / 2, 1); // L/2 se BxH=S minore uguale di 0.09
                    } else {
                        $caditoie_equiv = round($data['lunghezza'] / 5, 1); // L/5 se BxH=S maggiore di 0.09
                    }
                }
                break;
        }

        $data['tagsIds'] = [$data['statocaditoia'], $data['tipopozzetto'], $data['recapito']];

        $timestamp_numero = explode('_', $data['caditoia_id'])[0];

        $data['time_stamp_pulizia'] = $date->setTimestamp(substr($timestamp_numero, 0, -3))->format('Y-m-d H:i:s');

        $street = Street::find($data['codice_via']);
        if ($street) {
            $item = Item::make([
                'id_sd' => $data['id_sd'],
                'id_da_app' => $data['caditoia_id'],
                'time_stamp_pulizia' => $data['time_stamp_pulizia'],
                'civic' => $data['ubicazione'],
                'latitude' => $data['lat'],
                'longitude' => $data['lng'],
                'accuracy' => (empty($data['tolleranza']) ? 0 : $data['tolleranza']),
                'altitude' => $data['altitude'],
                'height' => $data['lunghezza'],
                'width' => $data['larghezza'],
                'depth' => $data['profondita'],
                'pic' => $data['caditoia_id'] . '.jpg',
                'note' => $data['note'],
                'note' => $data['note'],
                'caditoie_equiv' => $caditoie_equiv
            ]);
            $item->street()->associate($street);
            $item->user()->associate($user);
            $item->save();
        }
        if (isset($data['tagsIds'])) {
            $item->tags()->sync($data['tagsIds']);
        }

        $ret['result'] = true;
        $ret['id'] = $item->id;
        return $ret;
    }

    // GEO.ZA ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function saveImage(Request $request)
    {
        try {
            $imagedata = $request->input('imagedata');
            $imageName = $request->input('imageName');
            $giorno = $request->input('cartellaDelGiorno');
            $cartellaDelGiorno = str_replace('-', '', $giorno);

            if (!Storage::disk('img_items')->exists($cartellaDelGiorno)) {
                Storage::disk('img_items')->makeDirectory($cartellaDelGiorno, 0775, true);
            }

            if (str_contains($imagedata, 'data:image/jpeg;base64,')) {
                $imagedata = str_replace('data:image/jpeg;base64,', '', $imagedata);
                $imagedata = str_replace(' ', '+', $imagedata);
            }

            if (Storage::disk('img_items')->exists($cartellaDelGiorno . '/' . $imageName)) {
                Storage::disk('img_items')->put($cartellaDelGiorno . '/' . $imageName . '_' . date('His'), base64_decode($imagedata));
            } else {
                Storage::disk('img_items')->put($cartellaDelGiorno . '/' . $imageName, base64_decode($imagedata));
            }
            return response()->json($imagedata, 200);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    // GEO.ZA ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    private function checkUser($id_user, $iduserhash)
    {
        $api_token = $iduserhash;

        if (empty($id_user) || empty($api_token)) {
            $ret['result'] = false;
            $ret['error'] = 'id_user and api_token necessari necessario in chiamata';
            $ret['api_token'] = $api_token;
            return $ret;
            exit;
        }

        $user = User::where('api_token', $api_token)->find($id_user);

        if (empty($user)) {
            $ret['result'] = false;
            $ret['error'] = 'utente non trovato o non autenticato';
            $ret['api_token'] = $api_token;
            return $ret;
            exit;
        }

        $ret['result'] = true;
        $ret['user'] = $user;

        return $ret;
    }

    /**
     * ritorna caditoie fotografate per una determinata data
     * @param Request la richiesta con data (Y-m-d) per filtrare dati
     * @return json dati di tutte le caditoie filtrate
     */

    // GEO.ZA ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getCaditoieScansionate(Request $request)
    {
        $data = $request->all()['data'];
        $user = $data['user'] ?? null;

        $giorno = $data['giorno']['_value'] ?? null;
        if (empty($giorno)) {
            $date = new \DateTime('now', new \DateTimeZone('Europe/Rome'));
            $giorno = $date->format('Y-m-d');
        } else {
            $date = \DateTime::createFromFormat('d/m/Y', $giorno);
            $giorno = $date->format('Y-m-d');
        }

        $tagTypes = DB::table('tag_types')->pluck('name');

        $tagKeys = $tagTypes->map(function ($tagName) {
            return strtolower($tagName) . '_tag_id';
        })->toArray();


        $items = Item::with('street', 'street.city', 'user')
            ->whereRaw('DATE_FORMAT(time_stamp_pulizia, "%Y-%m-%d")="' . $giorno . '"')
            ->when($user, function ($query, $user) {
                return $query->where('user_id', $user);
            })
            ->get();

        $caditoie = [];
        $row = 0;
        foreach ($items as $i) {
            $itemTags = DB::table('item_tag')->where('item_id', $i->id)->first();
            foreach ($tagKeys as $tagKey) {
                if ($itemTags && isset($itemTags->$tagKey)) {
                    $tagName = DB::table('tags')
                        ->where('id', $itemTags->$tagKey)
                        ->value('name');
                    $caditoie[$row][$tagKey] = $tagName;
                }
            }
            $caditoie[$row]['data_caditoia'] = $i->time_stamp_pulizia;
            $caditoie[$row]['ubicazione'] = $i->civic;
            $caditoie[$row]['strada_nome'] = $i->street->name;
            $caditoie[$row]['comune_nome'] = $i->street->city->name;
            $caditoie[$row]['provincia_id'] = $i->street->city->district;
            $caditoie[$row]['caditoie_lat'] = $i->latitude;
            $caditoie[$row]['caditoie_lng'] = $i->longitude;
            $caditoie[$row]['caditoie_altitude'] = $i->altitude;
            $caditoie[$row]['foto_id'] = env('APP_URL') . '/img_items/' . date('Ymd', strtotime($i->time_stamp_pulizia)) . '/' . $i->pic;
            $caditoie[$row]['caditoie_note'] = $i->note;
            $caditoie[$row]['id'] = $i->id;
            $row++;
        }

        $ret['result'] = true;
        $ret['caditoie'] = $caditoie;
        return response()->json($ret, 200);
    }

    /**
     * ritorna caditoie fotografate per una determinata data ed una determinata via
     * @param Request la richiesta con giorniindietro e codicevia per filtrare i dati
     * @return json dati di tutte le caditoie filtrate
     */

    // GEO.ZA ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getCaditoieScansionatePerVia(Request $request)
    {
        $data = $request->all()['data'];

        $user_id = $data['user'] ?? null;

        $giorniindietro = $data['giorniindietro'];
        $codicevia = $data['codice_via'];
        if (empty($giorniindietro)) {
            $giorniindietro = 7;
        }

        if (empty($codicevia)) {
            $ret['result'] = false;
            $ret['error'] = "Parametro strada mancante";
            return response()->json($ret, 200);
        }

        $items = Item::with('street', 'street.city', 'itemTag', 'user')
            ->where('street_id', $codicevia)
            ->whereRaw('DATE_FORMAT(time_stamp_pulizia, "%Y-%m-%d") >= ?', [Carbon::now()->subDays($giorniindietro)->toDateString()])
            ->when($user_id, function ($query, $user_id) {
                return $query->where('user_id', $user_id);
            })
            ->get();
        $caditoie = [];
        $row = 0;
        foreach ($items as $i) {
            $itemTag = $i->itemTags; // Questo dovrebbe darti l'oggetto ItemTag associato all'Item corrente
            $tagTypes = TagType::all();

            foreach ($tagTypes as $tagType) {
                $columnName = strtolower($tagType->name) . '_tag_id';
                if (isset($itemTag[$columnName]) && $itemTag[$columnName] !== null) {
                    $tag = Tag::find($itemTag[$columnName]);

                    if ($tag) {
                        $key = strtolower($tagType->name) . '_nome';
                        $caditoie[$row][$key] = $tag->name;
                    }
                }
            }
            $caditoie[$row]['id'] = $i->id;
            $caditoie[$row]['data_caditoia'] = $i->time_stamp_pulizia;
            $caditoie[$row]['caditoie_civico'] = $i->civic;
            $caditoie[$row]['user'] = $i->user->name;
            $row++;
        }

        $ret['result'] = true;
        $ret['caditoie'] = $caditoie;
        return response()->json($ret, 200);
    }

    public function testPostConBearer(Request $request)
    {
        $data = $request->all();
        $data['note'] = 'BEARER PRESENTE';
        $data['result'] = true;
        return response()->json($data, 200);
    }

    public function testPostSenzaBearer(Request $request)
    {
        $data = $request->all();
        $data['note'] = 'test senza bearer';
        $data['result'] = true;
        return response()->json($data, 200);
    }
}
