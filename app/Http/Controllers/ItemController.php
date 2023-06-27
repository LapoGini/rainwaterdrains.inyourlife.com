<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ItemRequest;
use App\Models\City;
use App\Models\Street;
use App\Models\Tag;
use Inertia\Inertia;


class ItemController extends Controller
{
    public function index() 
    {
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->paginate(50);
        // TODO: caricare con ajax prima comuni, poi strade nel form di modifica
        $streets = Street::with('city')->get();
        $tags = Tag::where('domain', 'item')->get();

        return view('pages.items.index', compact('items', 'streets', 'tags'));
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


    public function update(ItemRequest $request, Item $item) : RedirectResponse
    {
        //$this->authorize('update', $item);
        $validated = $request->validated();
        
        $street = Street::find($validated['street_id']);
        if($street) {
            $item->street()->associate($street)->save();
        }
        
        $item->update($validated);
        
        if(isset($validated['tagsIds'])){
            $item->tags()->sync($validated['tagsIds']);
        }

        return redirect(route('pages.items.index'));
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
