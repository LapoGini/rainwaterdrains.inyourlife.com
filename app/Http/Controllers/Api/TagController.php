<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

use App\Utils\Functions;

class TagController extends Controller
{

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
}
