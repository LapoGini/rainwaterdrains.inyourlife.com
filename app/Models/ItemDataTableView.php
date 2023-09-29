<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ItemDataTableView extends Model
{
    use HasFactory;

    public function street() {
        return $this->belongsTo(Street::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function itemDataTableViewQuery() {

        DB::statement('DROP VIEW IF EXISTS item_data_table_views');

        //recupero tutti i tipi di tag
        $types = TagType::pluck('name', 'id');

        //query per specificare le colonne della tabella
        $createViewQuery = DB::table('items AS i')
                                ->select(
                                    'i.id AS id',
                                    'i.id_sd AS id_sd',
                                    'i.id_da_app AS id_da_app',
                                    'i.time_stamp_pulizia AS time_stamp_pulizia',
                                    'i.caditoie_equiv AS caditoie_equiv',
                                    'i.civic AS civic',
                                    'i.longitude AS longitude',
                                    'i.latitude AS latitude',
                                    'i.altitude AS altitude',
                                    'i.accuracy AS accuracy',
                                    'i.height AS height',
                                    'i.width AS width',
                                    'i.depth AS depth',
                                    'i.pic AS pic',
                                    'i.note AS note',
                                    'i.street_id AS street_id',
                                    's.name AS street_nome',
                                    'c.id AS city_id',
                                    'c.name AS city_nome',
                                    'u.name AS user_nome',
                                    'i.user_id AS user_id',
                                    'i.cancellabile AS cancellabile',
                                    'i.deleted_at AS deleted_at',
                                    'i.created_at AS created_at',
                                    'i.updated_at AS updated_at',
                                )
                                ->from('items AS i')
                                ->join('streets AS s', 'i.street_id', '=', 's.id')
                                ->join('cities AS c', 's.city_id', '=', 'c.id')
                                ->join('users AS u', 'i.user_id', '=', 'u.id')
                                ->leftJoin('item_tag AS it', 'i.id', '=', 'it.item_id');

                                foreach($types as $type) {
                                    $tagType = strtolower($type);
                                    $tagTypeColumn = $tagType . '_tag_id';
                                    $tagType_id = strtolower($type) . '_id';

                                    $createViewQuery->leftJoin("tags AS $tagType", "$tagType.id", "=", "it.$tagTypeColumn");
                                    $createViewQuery->addSelect("$tagType.name AS $tagType");
                                    $createViewQuery->addSelect("$tagType.id AS $tagType_id");

                                }

        
        // Creare la vista nel database utilizzando i risultati
        $createViewQuerySQL = $createViewQuery->toSql();
        $createViewSQL = "CREATE VIEW item_data_table_views AS $createViewQuerySQL";


        // DA REINSERIRE PER CREARE LA VISTA
        DB::statement($createViewSQL);
        
    }
}
