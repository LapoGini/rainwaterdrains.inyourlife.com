<?php

namespace App\Http\Controllers;

use App\Models\Caditoie;
use App\Models\ItemTag;
use App\Models\Item;
use App\Models\Tag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class ItemTagController extends Controller
{
    public function importaDati()
    {
        $datiVecchiaTabella = DB::table('RWD_CADITOIE')->get();

        foreach ($datiVecchiaTabella as $dato) {
            $item_id = $dato->id;

            $tag_id_stato = DB::table('RWD_STATI')->where('stato_id', $dato->stato_id)->value('stato_id');

            $tag_id_tipo_pozzetto = DB::table('RWD_TIPI_POZZETTO')->where('tipopozzetto_id', $dato->tipo_pozzetto_id)->value('tipopozzetto_id');

            $tag_id_recapito = DB::table('RWD_RECAPITI')->where('recapito_id', $dato->recapito)->value('recapito_id');

            switch ($tag_id_recapito) {
                case 1: // Fognatura bianca
                    $recapito = 1;
                    break;
                case 2: // Fognatura nera
                    $recapito = 2;
                    break;
                case 3: // Fognatura mista
                    $recapito = 3;
                    break;
                default:
                    $recapito = $tag_id_recapito;
                    break;
            }

            switch ($tag_id_stato) {
                case 1: // Funzionante
                    $stato = 37;
                    break;
                case 2: // Rotta
                    $stato = 38;
                    break;
                case 3: // Bloccata
                    $stato = 39;
                    break;
                case 4: // Cemento
                    $stato = 10;
                    break;
                case 5: // Radici
                    $stato = 11;
                    break;
                case 6: // Non scarica
                    $stato = 12;
                    break;
                case 7: // Fondo rotto
                    $stato = 13;
                    break;
                case 8: // Macchina sopra
                    $stato = 14;
                    break;
                default:
                    $stato = $tag_id_stato;
                    break;
            }

            switch ($tag_id_tipo_pozzetto) {
                case 1: // caditoia
                    $tipo_pozzetto = 4;
                    break;
                case 2: // Bocca di lupo
                    $tipo_pozzetto = 5;
                    break;
                case 4: // Griglia
                    $tipo_pozzetto = 6;
                    break;
                case 5: // Funzionante
                    $tipo_pozzetto = 7;
                    break;
                default:
                    $tipo_pozzetto = $tag_id_tipo_pozzetto;
                    break;
            }

            if ($tag_id_stato && $tag_id_tipo_pozzetto && $tag_id_recapito) {
                $item = Item::find($item_id);

                $item->tags()->attach([$recapito, $tipo_pozzetto, $stato]);
            }
        }
    }

}
