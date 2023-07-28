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
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTablesEditor;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index(ItemsDataTable $dataTable, FilteredDataRequest $request) 
    {
  
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->get();

        $selectedClient = request()->query('client');
        $selectedComune = request()->query('comune');

        $comuni = City::join('users', 'cities.user_id', '=', 'users.id')->where('users.id',  $selectedClient)->get();
        $streets = Street::join('cities', 'cities.id', '=', 'streets.city_id')->join('users', 'cities.user_id', '=', 'users.id')->where('users.id', $selectedComune)->select('streets.*')->get();
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


        return $dataTable->with('richiesta', $request->all())->render('pages.Items.index', compact('items', 'clients', 'operators', 'streets', 'comuni', 'tags', 'itemsDate', 'tagTypes', 'groupedTags', 'groupedTagsType'));
    }

    public function createLinkPathFromImg_Item($item) {
        $linkPath = env('APP_URL') . '/img_items/' . date('Ymd', strtotime($item->time_stamp_pulizia)) . '/' . $item->pic;
        return $linkPath;
    }

    public function getHtmlCityByClient($id) {
        $comuni = City::whereHas('user', function ($query) use ($id) {
            $query->where('users.id', $id);
        })->get();
        echo '<option value="">Tutti</option>';
        foreach($comuni as $comune) {
            echo '<option value="'.$comune->id.'">'.$comune->name.'</option>';
        };
    }

    public function getHtmlStreetByCity($city_id) {

        $streets = Street::where('city_id', $city_id)->get();

        echo '<option value="">Tutti</option>';
        foreach($streets as $street) {
            echo '<option value="'.$street->id.'">'.$street->name.'</option>';
        };
    }

    /*private function getItems(FilteredDataRequest $request) 
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
            $query->whereHas('street.city', function($query) use ($clientId) {
                $query->where('user_id', $clientId);
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

        //QUI
        return $items;
    }*/

    public function createZipFileFromImg_Items() 
    {

        $ret['success'] = false;
        $ret['data'] = [];

        $items =  $filteredItemsSession = Session::get('filteredItems');
        $filteredItems = Item::with('street', 'street.city', 'tags', 'user')
                ->whereIn('id', $filteredItemsSession)
                ->orderBy('id', 'DESC')
                ->get();

        $zip = new ZipArchive;

        $zipFileName = '/downloads/' . time() . '.zip';
        
        $zipFilePath = Storage::disk('img_items')->path($zipFileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
            foreach ($filteredItems as $item) {

                // Recupera la cartella corrispondente al giorno dell'immagine
                $dateTime = date('Ymd', strtotime($item->time_stamp_pulizia));
                $folderPath = Storage::disk('img_items')->path('');

                // Recupera l'immagine
                $imagePath = $folderPath . $dateTime . '/';
                $relativeNameInZipFile = $item->pic;

                //qua il controllo sull'immagine
                if (file_exists($imagePath . $item->pic)) {
                    $zip->addFile($imagePath . $item->pic, $relativeNameInZipFile);
                } else {
                    $ret['data'][] = $relativeNameInZipFile;
                }
            }
            $zip->close();
            if(!empty($ret['data'])) {
                $ret['success'] = false;
                $ret['message'] = 'Immagini non trovate!';
            } else {
                $ret['success'] = true;
                $ret['message'] =  'File Zip generato con successo';
                $ret['data'] = base64_encode(file_get_contents($zipFilePath));
            }
        } else {
            $ret['message'] = 'Impossibile creare il file Zip';
        }
        return json_encode($ret);
    }

    public function deleteSewers()
    {
        $ret['success'] = false;

        $ret['data']['cancellabile'] = [];
        $ret['data']['non_cancellabile'] = [];
        
        $ids = [];

        $ids = Session::get('filteredItems');

        $items = Item::whereIn('id', $ids)->get();

        foreach($items as $item) {
            if ($item->cancellabile == null) {
                $item->cancellabile = Carbon::now()->setTimezone('Europe/Rome');
                $item->save();
                $ret['data']['cancellabile'][] = $item->id;
            } else {
                $ret['data']['non_cancellabile'][] = $item->id;
            }
        }
        
        if (!empty($ret['data']['cancellabile'])) {
            $ret['success'] = true;
            $ret['message'] = 'Hai reso cancellabili alcune caditoie!';
        } else {
            $ret['message'] = 'Impossibile rendere cancellabili alcune caditoie!';
        }
        
        return json_encode($ret);
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

    public function edit(ItemsDataTable $dataTable, Item $item)
    {
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->get();
        $comuni = City::all();

        $strada_caditoia =  Street::find($item->street_id);

        $streets = Street::with('city')->where('city_id',$strada_caditoia->city_id)->get();
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

        $item->pic_link = $this->createLinkPathFromImg_Item($item);

        $filteredItemsSession = Session::get('filteredItems');
        $prevItemId = null;
        $nextItemId = null;
        
        $currentIndex = array_search($item->id, $filteredItemsSession);
        if ($currentIndex !== false) {
            $prevItemId = ($currentIndex > 0) ? $filteredItemsSession[$currentIndex - 1] : null;
            $nextItemId = ($currentIndex < count($filteredItemsSession) - 1) ? $filteredItemsSession[$currentIndex + 1] : null;
        }

        return view('pages.Items.edit', compact('item', 'items', 'clients', 'operators', 'streets', 'comuni', 'tags', 'itemsDate', 'tagTypes', 'groupedTags', 'groupedTagsType', 'prevItemId', 'nextItemId'));
    }

    public function view(ItemsDataTable $dataTable, Item $item)
    {
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->get();
        $comuni = City::all();

        $strada_caditoia =  Street::find($item->street_id);

        $streets = Street::with('city')->where('city_id',$strada_caditoia->city_id)->get();
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

        $item->pic_link = $this->createLinkPathFromImg_Item($item);
        $filteredItemsSession = Session::get('filteredItems');
        $prevItemId = null;
        $nextItemId = null;

        $currentIndex = array_search($item->id, $filteredItemsSession);
        if ($currentIndex !== false) {
            $prevItemId = ($currentIndex > 0) ? $filteredItemsSession[$currentIndex - 1] : null;
            $nextItemId = ($currentIndex < count($filteredItemsSession) - 1) ? $filteredItemsSession[$currentIndex + 1] : null;
        }
        

        return view('pages.Items.view', compact('item', 'items', 'clients', 'operators', 'streets', 'comuni', 'tags', 'itemsDate', 'tagTypes', 'groupedTags', 'groupedTagsType', 'prevItemId', 'nextItemId'));
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
        
        if(isset($validated['tags'])){
            $item->tags()->sync($validated['tags']);
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