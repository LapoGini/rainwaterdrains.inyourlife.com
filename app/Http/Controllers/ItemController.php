<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\FilteredDataRequest;
use App\Models\City;
use App\Models\Street;
use App\Models\User;
use App\Models\Tag;
use App\DataTables\ItemsDataTable;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function index(ItemsDataTable $dataTable) 
    {
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->paginate(50);
        $streets = Street::with('city')->get();
        $comuni = City::all();
        $clients = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 3)->select('users.*')->get();
        $tagTypes = Tag::where('domain', 'item')->distinct('type')->pluck('type');
        $operators = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 2)->select('users.*')->get();
        $itemsDate = Item::pluck('time_stamp_pulizia');

        $tags = Tag::where('domain', 'item')->get();
        $groupedTags = [];
        foreach ($items as $item) {
            $itemTags = $item->tags;
            foreach ($itemTags as $tag) {
                $type = $tag->type;
                $groupedTags[$item->id][$type][] = $tag;
            }
        }

        $groupedTagsType = [];
        foreach ($tagTypes as $type) {
            $tags = Tag::where('domain', 'item')->where('type', $type)->get();
            $groupedTagsType[$type] = $tags;
        }

        //dd($groupedTags);

        return $dataTable->render('pages.Items.index', compact('items', 'clients', 'operators', 'streets', 'comuni', 'tags', 'itemsDate', 'tagTypes', 'groupedTags', 'groupedTagsType'));
        //return $dataTable->render('pages.items.index');
    }

    // devo recuperare la colonna id_da_app che si trova in items
        // dopo di che la devo trasformare in un array di elementi che sono separati da '-'
        // facendo explode o implode devo recuperare solamente il primo dato cioè il time stamp
        // devo recuperare il dato e trasformarlo in una data effettiva
        // devo pushare tutte le date dentro ad un array

    public function filterData(FilteredDataRequest $request)
    {
        $clientId = $request->input('clientId');
        $comuneId = $request->input('comuneId');
        $streetId = $request->input('streetId');
        $fromDateId = $request->input('fromDateId');
        $toDateId = $request->input('toDateId');
        $operatorId = $request->input('operatorId');
        $selectedTags = $request->input('tags');

        $query = Item::with('street', 'street.city', 'tags', 'user');

        if ($clientId) {
            $query->whereHas('user', function($query) use ($clientId) {
                $query->where('id', $clientId);
            });
        }

        if ($comuneId) {
            $query->whereHas('street.city', function($query) use ($comuneId) {
                $query->where('id', $comuneId);
            });
        }

        if ($streetId) {
            $query->whereHas('street', function($query) use ($streetId) {
                $query->where('id', $streetId);
            });
        }

        if ($fromDateId && $toDateId) {
            $fromDateId = new Carbon($fromDateId);
            $toDateId = new Carbon($toDateId);
            $query->whereBetween('time_stamp_pulizia', [$fromDateId->startOfDay(),  $toDateId->endOfDay()]);
        }

        if ($operatorId) {
            $query->whereHas('user', function($query) use ($operatorId) {
                $query->where('id', $operatorId);
            });
        }

        if ($selectedTags) {
            $query->whereHas('tags', function($query) use ($selectedTags) {
                $query->whereIn('tags.id', $selectedTags);
            });
        }

        $items = $query->orderBy('id', 'DESC')->get();
        
        $groupedTags = [];

        foreach ($items as $item) {
            $itemTags = $item->tags;

            foreach ($itemTags as $tag) {
                $type = $tag->type;
                $groupedTags[$item->id][$type][] = $tag;
            }
        }

        return view('pages.Items.filtered_data', compact('items', 'groupedTags'));
    }


    // public function store(ItemRequest $request) : RedirectResponse
    // {
    //     $validated = $request->validated();
    //     $street = Street::find($validated['street_id']);
    //     if($street) {
    //         $item = Item::create($validated);
    //         $item->street()->associate($street)->save();
    //     }
    //     if(isset($validated['tagsIds'])){
    //         $item->tags()->sync($validated['tagsIds']);
    //     }
    //     return redirect(route('pages.items.index'));
    // }

    public function edit(Item $item)
    {
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->paginate(50);
        $streets = Street::with('city')->get();
        $comuni = City::all();
        $clients = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 3)->select('users.*')->get();
        $tagTypes = Tag::where('domain', 'item')->distinct('type')->pluck('type');
        $operators = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 2)->select('users.*')->get();
        $itemsDate = Item::pluck('time_stamp_pulizia');


        $tags = Tag::where('domain', 'item')->get();
        $groupedTags = [];
        foreach ($items as $el) {
            $itemTags = $el->tags;
            foreach ($itemTags as $tag) {
                $type = $tag->type;
                $groupedTags[$el->id][$type][] = $tag;
            }
        }

        $groupedTagsType = [];
        foreach ($tagTypes as $type) {
            $tags = Tag::where('domain', 'item')->where('type', $type)->get();
            $groupedTagsType[$type] = $tags;
        }
        

        return view('pages.Items.edit', compact('item', 'items', 'clients', 'operators', 'streets', 'comuni', 'tags', 'itemsDate', 'tagTypes', 'groupedTags', 'groupedTagsType'));
    }

    public function update(ItemRequest $request, Item $item) : RedirectResponse
    {
        //$this->authorize('update', $item);
        $validated = $request->validated();
        
        $street = Street::find($validated['street']);
        if($street) {
            $item->street()->associate($street)->save();
        }
        
        $item->update($validated);
        
        if(isset($validated['tagsIds'])){
            $item->tags()->sync($validated['tagsIds']);
        }

        return to_route('items.index');
    }

    public function destroy(Item $item) : RedirectResponse
    {
        //$this->authorize('delete', $item);
        $item->delete();
        return redirect(route('pages.items.index'));
    }

    // public function exportCSV() 
    // {
    //     $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->get();
        
    //     $fileName = 'caditoie.csv';

    //     $headers = array(
    //         "Content-type"        => "text/csv",
    //         "Content-Disposition" => "attachment; filename=$fileName",
    //         "Pragma"              => "no-cache",
    //         "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
    //         "Expires"             => "0"
    //     );

        
    //     $columns = [
    //         "Comune",
    //         "Via",
    //         "Lunghezza[L]",
    //         "Larghezza[S]",
    //         "Profondita[P]",
    //         "Volume",
    //         "Rapporto L/S",
    //         "Latitudine",
    //         "Longitudine",
    //         "Altitudine",
    //         "Punto su mappa",
    //         "Data",
    //         "Note",
    //         "FotoFile"
    //     ];

    //     $callback = function() use($items, $columns) {
    //         $file = fopen('php://output', 'w');
    //         fputcsv($file, $columns);

    //         foreach ($items as $item) {
    //             $row['Comune'] = $item->street->city->name;
    //             $row['Via'] = $item->street->name;
    //             $row['Lunghezza[L]'] = $item->height;
    //             $row['Larghezza[S]'] = $item->width;
    //             $row['Profondita[P]'] = $item->depth;
    //             $row['Volume'] = $item->height*$item->width*$item->depth;
    //             $row['Rapporto L/S'] = $item->height/$item->width;
    //             $row['Latitudine'] = $item->latitude;
    //             $row['Longitudine'] = $item->longitude;
    //             $row['Altitudine'] = $item->altitude;
    //             $row['Punto su mappa'] = `https://www.google.it/maps?q=$item->latitude,$item->longitude`;
    //             $row['Data'] = $item->created_at;
    //             $row['Note'] = $item->note;
    //             $row['FotoFile'] = `https://geolocalizzazionezanetti.it/RWD/files/$item->id/$item->pic`;

    //             fputcsv($file, array($row['Title'], $row['Assign'], $row['Description'], $row['Start Date'], $row['Due Date']));
    //         }

    //         fclose($file);
    //     };

    //     return response()->stream($callback, 200, $headers);
    // }
}
