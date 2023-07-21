<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\ItemDataTableView;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TagRequest;
use Illuminate\Support\Facades\DB;
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
        $validated['type'] = strtolower($validated['type']);
        Tag::create($validated);

        //distruzione della tabella vista esistente
        DB::statement('DROP VIEW IF EXISTS item_data_table_views');
        // Creazione della tabella vista item_data_table_views
        $itemDataTableView = new ItemDataTableView();
        $itemDataTableView->itemDataTableViewQuery();


        return to_route('tags.index', $domain);
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
        return to_route('tags.index', $domain);
    }

    public function destroy(String $domain, Tag $tag) : RedirectResponse
    {
        //distruzione della tabella vista esistente
        DB::statement('DROP VIEW IF EXISTS item_data_table_views');

        $tag->delete();

        // Creazione della tabella vista item_data_table_views
        $itemDataTableView = new ItemDataTableView();
        $itemDataTableView->itemDataTableViewQuery();
        return to_route('tags.index', $domain);
    }
}
