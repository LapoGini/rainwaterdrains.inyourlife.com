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
        $datiVecchiaTabella = Strade::orderBy('strada_id')->get();

        foreach ($datiVecchiaTabella as $dato) {
            $normalizedOldComuneId = strtolower($this->normalizeString($dato->comune_id));
            $city = City::whereRaw('LOWER(REPLACE(REPLACE(REPLACE(name, " ", ""), "-", ""), "\'", "")) = ?', [$normalizedOldComuneId])->first();

            $dataToCreate = [
                'name' => $dato->strada_nome,
                'strada_id_vecchio_db' => $dato->strada_id,
            ];

            if ($city) {
                $dataToCreate['city_id'] = $city->id;
                $dataToCreate['comune_id_vecchio_db'] = $dato->comune_id;
            } else {
                $dataToCreate['city_id'] = 694;
                $dataToCreate['comune_id_vecchio_db'] = $dato->comune_id;
                Log::error('Impossibile trovare il comune con comune_nome: ' . $dato->comune_id . ' per strada_id: ' . $dato->strada_id);
            }

            Street::create($dataToCreate);
            Log::info('Importazione eseguita per strada_id: ' . $dato->strada_id);
        }
    }

    private function normalizeString($string) 
    {
        $string = str_replace("'", "", $string);
        $string = preg_replace('/[^A-Za-z0-9]/', '', $string);
        return $string;
    }

}