<?php

namespace App\Http\Controllers;


use App\Models\Comune;
use App\Models\City;
use App\Models\TempComuni;
use App\Models\Cliente;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class ComuneController extends Controller
{
    public function importaDati()
    {
        Comune::chunk(100, function ($datiVecchiaTabella) {
            foreach ($datiVecchiaTabella as $dato) {

                $comuneTemporaneo = TempComuni::where('comune_nome', $dato->comune_nome)->first();

                if ($comuneTemporaneo) {
                    
                    $cliente = Cliente::where('CLIENTE', $dato->CLIENTE)->first();

                    if ($cliente->id == 1) {
                        $cliente->id = 3;
                    } elseif ($cliente->id == 2) {
                        $cliente->id = 4;
                    } elseif ($cliente->id == 3) {
                        $cliente->id = 5;
                    }

                    // Se il cliente esiste, crea la nuova riga nella tabella "City"
                    if ($cliente) {
                        City::create([
                            'id' => $comuneTemporaneo->id,
                            'name' => $dato->comune_id,
                            'district' => $dato->provincia_id,
                            'pics' => $dato->foto,
                            'user_id' => $cliente->id,
                            'status' => $dato->stato,
                        ]);
                    } else {
                        // Cliente non trovato, puoi gestire questa situazione qui
                    }
                } else {
                    // Comune temporaneo non trovato, puoi gestire questa situazione qui
                }
            }
        });
    }
}
