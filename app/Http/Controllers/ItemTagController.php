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
        $items = Item::all();

        //$items = Item::whereBetween('id', [32119, 50978])->get();

        foreach ($items as $item) {
            $oldData = DB::table('RWD_CADITOIE')->where('id', $item->id_vecchio_db)->first();

            if ($oldData) {
                $recapito_tag_id = $oldData->recapito;

                $statoTagMapping = [
                    1 => 7,
                    2 => 8,
                    3 => 9,
                    4 => 10,
                    5 => 11,
                    6 => 12,
                    7 => 13,
                    8 => 14,
                ];

                $stato_tag_id = isset($statoTagMapping[$oldData->stato_id]) ? $statoTagMapping[$oldData->stato_id] : null;

                $tipoPozzettoMapping = [
                    1 => 4,
                    2 => 5,
                    4 => 6
                ];

                $tipologia_tag_id = isset($tipoPozzettoMapping[$oldData->tipo_pozzetto_id]) ? $tipoPozzettoMapping[$oldData->tipo_pozzetto_id] : null;

                // Assumi che Item_tag sia un modello Eloquent
                ItemTag::create([
                    'item_id' => $item->id,
                    'recapito_tag_id' => $recapito_tag_id,
                    'stato_tag_id' => $stato_tag_id,
                    'tipologia_tag_id' => $tipologia_tag_id,
                ]);
            }
        }
    }

}
