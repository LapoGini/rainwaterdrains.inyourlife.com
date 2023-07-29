<?php

namespace App\Http\Controllers;

use App\Models\Strade;
use App\Models\Street;
use App\Models\City;
use Illuminate\Support\Facades\Log;


class StradeController extends Controller
{
    public function importaDati()
    {
        Strade::orderBy('strada_id')->chunk(100, function ($datiVecchiaTabella) {
            foreach ($datiVecchiaTabella as $dato) {

                $cities = City::where('name', $dato->comune_id)->first();

                if ($cities) {
                    Street::create([
                        'id' => $dato->strada_id,
                        'name' => $dato->strada_nome,
                        'city_id' => $cities->id,
                    ]);
                    Log::info('Importazione eseguita per strada_id: ' . $dato->strada_id);
                } else {

                    Log::error('Impossibile trovare il comune con comune_nome: ' . $dato->comune_id . ' per strada_id: ' . $dato->strada_id);
                }
            }
        });
    }
}