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

        //recupero tutti i tipi di tag
        $allTagTypes = DB::table('tags')->distinct()->pluck('type')->toArray();

        //query per specificare le colonne della tabella
        $createViewQuery = DB::table('jc6n141b_zanetti_dev.items AS i')
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
                            'i.user_id AS user_id',
                            'i.cancellabile AS cancellabile',
                            'i.deleted_at AS deleted_at',
                            'i.created_at AS created_at',
                            'i.updated_at AS updated_at'
                        );

        // sottoquery dinamiche per i nomi
        foreach ($allTagTypes as $tagType) {
            $createViewQuery->selectRaw("(
                SELECT GROUP_CONCAT(`tags`.`name` SEPARATOR ',')
                FROM `tags`
                JOIN `item_tag` ON `tags`.`id` = `item_tag`.`tag_id`
                WHERE `tags`.`type` = '$tagType' AND `item_tag`.`item_id` = `i`.`id`
            ) AS $tagType");
        }

        // sottoquery dinamiche per gli id
        foreach ($allTagTypes as $tagType) {
            $tagTypeString = $tagType . '_id';
            $createViewQuery->selectRaw("(
                SELECT GROUP_CONCAT(`tags`.`id` SEPARATOR ',')
                FROM `tags`
                JOIN `item_tag` ON `tags`.`id` = `item_tag`.`tag_id`
                WHERE `tags`.`type` = '$tagType' AND `item_tag`.`item_id` = `i`.`id`
            ) AS $tagTypeString");
        }

        
        // Creare la vista nel database utilizzando i risultati
        $createViewQuerySQL = $createViewQuery->toSql();
        $createViewSQL = "CREATE VIEW item_data_table_views AS $createViewQuerySQL";
        DB::statement($createViewSQL);
        
    }
}
