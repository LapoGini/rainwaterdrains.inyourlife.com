<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

use App\Utils\Functions;

class TagController extends Controller
{

    public function getAll(string $domain) 
    {
        $results = DB::table('tags')
            ->join('tag_types as tt', 'tags.type_id', '=', 'tt.id')
            ->where('tags.domain', $domain)
            ->select(
                'tags.id as tag_id',
                'tags.name as tag_name',
                'tags.description',
                'tags.domain',
                'tt.id as tag_type_id',
                'tt.name as tag_type_name'
            )
            ->get();

        $groupedResults = $results->groupBy('tag_type_name')->map(function ($group) {
            return [
                'tag_type_id' => $group->first()->tag_type_id,
                'tag_type_name' => $group->first()->tag_type_name,
                'tags' => $group->map(function ($item) {
                    return [
                        'tag_id' => $item->tag_id,
                        'tag_name' => $item->tag_name,
                        'description' => $item->description,
                        'domain' => $item->domain,
                    ];
                })->values()
            ];
        })->values();
        
        return Functions::setResponse($groupedResults, 'Tags non trovati');
    }

/*
    public function getAll(string $domain) 
    {
        $tags = Tag::where('domain', $domain)->orderBy('id', 'DESC')->get()->groupBy('type');
        
        return Functions::setResponse($tags, 'Tags non trovati');
    }

    public function getByType(string $domain, string $type) 
    {
        $tags = Tag::where('domain', $domain)->where('type', $type)->orderBy('id', 'DESC')->get();

        return Functions::setResponse($tags, 'Tags non trovati');
    }

    public function getRecapiti() 
    {
        $tags = Tag::where('type', 'Recapito' )->select('id as recapito_id','name as recapito_nome')->get();

        return Functions::setResponse($tags, 'Nessun Recapito Trovato');
    }

    public function getStati() 
    {
        $tags = Tag::where('type', 'Stato' )->select('id as stato_id','name as stato_nome')->get();

        return Functions::setResponse($tags, 'Nessun Stato Trovato');
    }

    public function getTipiPozzetto() 
    {
        $tags = Tag::where('type', 'Tipologia' )->select('id as tipopozzetto_id','name as pozzetto_nome')->get();

        return Functions::setResponse($tags, 'Nessun Tipologia Trovato');
    }
*/
}
