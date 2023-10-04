<?php

namespace App\Http\Controllers;

use App\Models\Caditoie;
use App\Models\Item;
use App\Models\Foto;
use App\Models\Street;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class CaditoieController extends Controller
{
    public function importaDati($da, $a)
    {

        setlocale(LC_NUMERIC, 'it_IT');
        $count = 0;

        for ($id = $da; $id <= $a; $id++) {
            $dato = Caditoie::find($id);

            if ($dato) {
                $foto = Foto::where('foto_id', $dato->foto_id)->first();
                $city = City::where('name', $dato->comune_id)->first();

                if ($city) {
                    $street = Street::where('strada_id_vecchio_db', $dato->codice_via)->first();

                    if ($street) {
                        $userMapping = [
                            2993 => 5,
                            2994 => 6,
                            2995 => 7,
                            2996 => 8,
                            2998 => 10,
                            2999 => 11,
                        ];
                        $userId = $userMapping[$dato->user_id] ?? null;

                        $imageName = null;

                        if ($foto && $foto->foto_absoluteurl) {
                            $imageName = $this->downloadAndSaveImage($foto->foto_absoluteurl);
                            if (!$imageName) {
                                var_dump('Errore nel salvataggio dell\'immagine per l\'ID: ' . $dato->id);
                                continue;
                            }
                        }

                        Item::create([
                            'id_vecchio_db' => $dato->id,
                            'id_sd' => null,
                            'id_da_app' => $dato->caditoie_id,
                            'time_stamp_pulizia' => $dato->caditoie_timestamp,
                            'caditoie_equiv' => $dato->caditoie_equiv,
                            'civic' => $dato->caditoie_ubicazione,
                            'longitude' => $dato->caditoie_lng,
                            'latitude' => $dato->caditoie_lat,
                            'altitude' => !empty($dato->caditoie_altitude) ? $dato->caditoie_altitude : null,
                            'accuracy' => 0,
                            'height' => str_replace(',', '.', $dato->lunghezza),
                            'width' => str_replace(',', '.', $dato->larghezza),
                            'depth' => str_replace(',', '.', $dato->profondita),
                            'pic' => $imageName,
                            'note' => $dato->caditoie_note,
                            'street_id' => $street->id,
                            'user_id' => $userId,
                            'cancellabile' => null,
                            'deleted_at' => null,
                        ]);
                        $count++;
                    } else {
                        var_dump('Impossibile trovare la strada con city_id: ' . $city->id . ' per id: ' . $dato->id);
                    }
                } else {
                    var_dump('Impossibile trovare la città con name: ' . $dato->comune_id . ' per id: ' . $dato->id);
                }
            } else {
                var_dump('Impossibile trovare il dato con id: ' . $id);
            }
        }
        var_dump("Totale importati: " . $count);
    }

    private function downloadAndSaveImage($imageUrl) {
        $client = new Client();
        $response = $client->get($imageUrl);
    
        if ($response->getStatusCode() == 200 && strpos($response->getHeader('Content-Type')[0], 'image') !== false) {

            $explodedUrl = explode('/', $imageUrl);
            $dateFolder = $explodedUrl[5];  // Ottiene la cartella della data
            $imageName = end($explodedUrl);  // Ottiene il nome del file
    
            if(!Storage::disk('img_items')->exists($dateFolder)) {
                Storage::disk('img_items')->makeDirectory($dateFolder, 0775, true);
            }
    
            // Controlla se il file esiste già
            if(Storage::disk('img_items')->exists($dateFolder.'/'.$imageName)) {
                $newImageName = $imageName.'_'.date('His');
            } else {
                $newImageName = $imageName;
            }

            Storage::disk('img_items')->put($dateFolder.'/'.$newImageName, $response->getBody());
    
            return $newImageName;  // Restituisci il nome del file per usarlo in seguito
        }
    
        return null;  // Restituisci null se qualcosa va storto
    }

}