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
        $comuni = Comune::all();

        foreach ($comuni as $comune) {
            City::create([
                'name' => $comune->comune_nome,
                'district' => $comune->provincia_id,
                'pics' => $comune->foto,
                'user_id' => 11,
                'status' => $comune->stato,
            ]);
        }
    }

}
