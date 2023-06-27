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
}
