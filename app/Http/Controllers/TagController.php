<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TagRequest;
use Inertia\Inertia;


class TagController extends Controller
{
    public function index() 
    {
        $domain = request()->route('domain');
        $tags = Tag::where('domain', $domain)->orderBy('id', 'DESC')->get()->groupBy('type');
        
        return view('pages.tags.index', compact('tags', 'domain'));
    }

    public function store(TagRequest $request, String $domain) : RedirectResponse
    {
        
        $validated = $request->validated();
        $validated['domain'] = $domain;
        Tag::create($validated);
        return redirect(route('pages.tags.index', $domain));
    }


    public function update(TagRequest $request, String $domain, Tag $tag) : RedirectResponse
    {
        //$this->authorize('update', $tag);

        $validated = $request->validated();
        $tag->update($validated);
        return redirect(route('pages.tags.index', $domain));
    }

    public function destroy(String $domain, Tag $tag) : RedirectResponse
    {
        //$this->authorize('delete', $tag);
        $tag->delete();
        return redirect(route('pages.tags.index', $domain));
    }
}
