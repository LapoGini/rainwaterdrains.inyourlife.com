<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\ItemTag;
use App\Models\TagType;
use App\Models\ItemDataTableView;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TagRequest;
use App\Http\Requests\TagTypeRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class TagController extends Controller
{
    public function index() 
    {
        $domain = request()->route('domain');
        $tags = Tag::where('domain', $domain)->orderBy('id', 'DESC')->get()->groupBy('type_id');

        $types = TagType::pluck('name', 'id');

        return view('pages.Tags.index', compact('tags', 'domain', 'types'));
    }

    public function create()
    {
        $domain = request()->route('domain');
        $tags = Tag::where('domain', $domain)->orderBy('id', 'DESC')->get()->groupBy('type_id');
        $types = TagType::pluck('name', 'id');
        $newName = request()->has('newName') ? request('newName') : null;
        $newDescription = request()->has('newDescription') ? request('newDescription') : null;
        $typeName = null;
        $newTagName = null;
        if (request()->has('newTag')) {
            $typeName = TagType::find(request('newTag'))->name;
            $newTagName = str_replace('_', ' ', $typeName);
        }
        $allTag = Tag::where('domain', $domain)->orderBy('id', 'DESC')->get();

        

        return view('pages.Tags.create', compact('domain', 'tags', 'allTag', 'types', 'newTagName', 'newName', 'newDescription'));
    }

    public function addNewTag()
    {
        $tagTypeName = $_REQUEST['tag_name'];
        $tagName = $_REQUEST['name'];
        $tagDescription = $_REQUEST['description'];

        $columns = Schema::getColumnListing('item_tag');

        $allRowsAreNull = false;
        $tagTypeId = null;
        
        foreach ($columns as $column) {
            
            if (!DB::table('item_tag')->whereNotNull($column)->exists() && Tag::where('type_id', $tagTypeId)->count() === 0) {

                $tagTypeNameSearch = str_replace('_tag_id', '', $column);
                $tagType = TagType::where('name', $tagTypeNameSearch)->first();
                if ($tagType) {
                    $tagTypeId = $tagType->id;
                }

                $allRowsAreNull = true;
                break;
            }
        }

        if ($allRowsAreNull) {

            Tag::where('type_id', $tagTypeId)->delete();
            TagType::where('id', $tagTypeId)->delete();

            // Elimina la colonna
            Schema::table('item_tag', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }

        $cleanedTypeName = strtolower(str_replace(' ', '_', $tagTypeName));

        // Salva il nuovo tag nella tabella tag_types
        $newTag = TagType::create([
            'name' => $cleanedTypeName,
        ]);

        Schema::table('item_tag', function (Blueprint $table) use ($cleanedTypeName) {
            $nameColumn = $cleanedTypeName . '_tag_id';

            $table->foreignId($nameColumn)->nullable();
        });

        $this->dropAndCreateView();
        
        return redirect()->route('tags.create', ['item', 'newTag' => $newTag->id, 'newName' => $tagName, 'newDescription' => $tagDescription])
                ->with('success', 'Nuovo tag aggiunto con successo.');
    }

    private function dropAndCreateView() {
        //distruzione della tabella vista esistente
        DB::statement('DROP VIEW IF EXISTS item_data_table_views');
        // Creazione della tabella vista item_data_table_views
        $itemDataTableView = new ItemDataTableView();
        $itemDataTableView->itemDataTableViewQuery();
    }

    public function store(TagRequest $request, String $domain) : RedirectResponse
    {
        $validated = $request->validated();
        $validated['domain'] = $domain;
        $validated['type_id'] = strtolower($validated['type_id']);

        $tag = Tag::create($validated);

        $itemTag = new ItemTag([
            'recapito_tag_id' => $tag->type_id === 'recapito' ? $tag->id : null,
            'stato_tag_id' => $tag->type_id === 'stato' ? $tag->id : null,
            'tipologia_tag_id' => $tag->type_id === 'tipologia' ? $tag->id : null,
        ]);

        $this->dropAndCreateView();

        return to_route('tags.index', $domain);
    }

    public function edit(String $domain, $tagId)
    {
        $tag = Tag::findOrFail($tagId);
        $domain = request()->route('domain');
        $tags = Tag::where('domain', $domain)->orderBy('id', 'DESC')->get()->groupBy('type_id');
        $types = TagType::pluck('name', 'id');
        return view('pages.Tags.edit', compact('tag', 'tags', 'domain', 'types'));
    }

    public function update(TagRequest $request, String $domain, Tag $tag) : RedirectResponse
    {
        //$this->authorize('update', $tag);

        $validated = $request->validated();

        // verificare se il tipo di tag è stato modificato
        $tipoPrecedente = $tag->type_id;

        $tag->update($validated);

        if ($tag->type_id !== $tipoPrecedente) {
            // Aggiornare gli ID dei tag nei record corrispondenti nella tabella item_tag

            $types = TagType::pluck('name', 'id');

            foreach($types as $type) {
                $tagName = strtolower($type);
                $columnName = strtolower($type) . '_tag_id';
                
                if ($tag->type_id === $tagName) {
                    ItemTag::where($columnName, $tag->id)->update([$columnName => null]);
                    
                    $tag->itemTags()->update([$columnName => $tag->id]);
                } else {
                    ItemTag::where($columnName, $tag->id)->update([$columnName => null]);
                }
            }
           
        }

        return to_route('tags.index', $domain);
    }

    public function destroy(String $domain, Tag $tag) : RedirectResponse
    {

        // DA REINSERIRE PER CREARE LA VISTA
        //distruzione della tabella vista esistente
        DB::statement('DROP VIEW IF EXISTS item_data_table_views');

        //Elimiare il Tag
        $tag->delete();

        $columnNameTagType = TagType::where('id', $tag->type_id)->value('name');
        $columnNameItemTag = $columnNameTagType . '_tag_id';

        $tagCountForType = Tag::where('type_id', $tag->type_id)->count();

        $tagType = TagType::find($tag->type_id);
        if ($tagCountForType === 0) {
            $tagType->delete();

            Schema::table('item_tag', function (Blueprint $table) use ($columnNameItemTag) {
                $table->dropColumn($columnNameItemTag);
            });
        }

        // Creazione della tabella vista item_data_table_views
        $itemDataTableView = new ItemDataTableView();
        $itemDataTableView->itemDataTableViewQuery();
        return to_route('tags.index', $domain);
    }
}
