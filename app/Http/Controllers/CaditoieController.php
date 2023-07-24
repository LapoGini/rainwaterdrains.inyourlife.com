<?php

namespace App\Http\Controllers;

use App\Models\Caditoie;
use App\Models\Item;
use App\Models\Foto;
use App\Models\Street;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class CaditoieController extends Controller
{
    public function importaDati($da, $a)
    {

        setlocale(LC_NUMERIC, 'it_IT');
        $count = 0;

        for ($id = $da; $id <= $a; $id++) {
            $dato = Caditoie::find($id);

            if ($dato) {

                $foto = Foto::where('tmp_caditoia_id', $dato->caditoie_id)->first();

                $city = City::where('name', $dato->comune_id)->first();

                if ($city) {
                    $street = Street::where('city_id', $city->id)->first();

                    if ($street) {
                        $user = User::where('id', $dato->user_id)->first();
                        var_dump($user->id);

                        Item::create([
                            'id' => $dato->id,
                            'id_sd' => null,
                            'id_da_app' => $dato->caditoie_id,
                            'time_stamp_pulizia' => $dato->caditoie_timestamp,
                            'caditoie_equiv' => $dato->caditoie_equiv,
                            'civic' => $dato->codice_via,
                            'longitude' => $dato->caditoie_lng,
                            'latitude' => $dato->caditoie_lat,
                            'altitude' => !empty( $dato->caditoie_altitude) ? $dato->caditoie_altitude : null,
                            'accuracy' => 0,
                            'height' => str_replace(',', '.', $dato->lunghezza),
                            'width' => str_replace(',', '.', $dato->larghezza),
                            'depth' => str_replace(',', '.', $dato->profondita),
                            'pic' => !empty($foto->tmp_caditoia_id) ? $foto->tmp_caditoia_id : null,
                            'note' => $dato->caditoie_note,
                            'street_id' => $street->id ? $street->id : null,
                            'user_id' => $user->id ? $user->id : null,
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
}

// ALTER TABLE RWD_CADITOIE MODIFY COLUMN street_id INT NULL DEFAULT NULL;

// ALTER TABLE RWD_CADITOIE MODIFY COLUMN user_id INT NULL DEFAULT NULL;