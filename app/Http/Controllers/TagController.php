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
        return view('pages.Tags.index', compact('tags', 'domain'));
    }

    public function create()
    {
        $domain = request()->route('domain');
        $tags = Tag::where('domain', $domain)->orderBy('id', 'DESC')->get()->groupBy('type');
        return view('pages.Tags.create', compact('domain', 'tags'));
    }

    public function store(TagRequest $request, String $domain) : RedirectResponse
    {
        $validated = $request->validated();
        $validated['domain'] = $domain;
        Tag::create($validated);
        return to_route('Tags.index', $domain);
    }

    public function edit(String $domain, $tagId)
    {
        $tag = Tag::findOrFail($tagId);
        $domain = request()->route('domain');
        $tags = Tag::where('domain', $domain)->orderBy('id', 'DESC')->get()->groupBy('type');
        return view('pages.Tags.edit', compact('tag', 'tags', 'domain'));
    }

    public function update(TagRequest $request, String $domain, Tag $tag) : RedirectResponse
    {
        //$this->authorize('update', $tag);

        $validated = $request->validated();
        $tag->update($validated);
        return to_route('Tags.index', $domain);
    }

    public function destroy(String $domain, Tag $tag) : RedirectResponse
    {
        //$this->authorize('delete', $tag);
        $tag->delete();
        return to_route('Tags.index', $domain);
    }
}
