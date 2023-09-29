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
use App\Models\TagType;
use App\Models\ItemTag;
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
        $types = TagType::pluck('name', 'id');

        //$itemModel = new Item;
        
        /*foreach($types as $typeId => $typeName) {
            $relationName = camel_case($typeName) . 'Tags';
            $relations[$relationName] = $itemModel->belongsToMany(Tag::class, 'item_tag', 'item_id', $typeName . '_tag_id')->as($relationName);
        }*/

        //$with = ['street', 'street.city', 'user'];

        $items = Item::with('street', 'street.city', 'user')->orderBy('id', 'DESC')->get();

        $itemsTag = DB::table('items AS i')
                        ->select(
                            'i.id AS id',
                            'i.id_sd AS id_sd',
                            'i.id_da_app AS id_da_app',
                            'i.time_stamp_pulizia AS time_stamp_pulizia',
                            'i.caditoie_equiv AS caditoie_equiv',
                            'i.civic AS civic',
                            'i.longitude AS longitude',
                            'i.latitude AS latitude',
                            'i.altitude AS altitude',
                            'i.accuracy AS accuracy',
                            'i.height AS height',
                            'i.width AS width',
                            'i.depth AS depth',
                            'i.pic AS pic',
                            'i.note AS note',
                            'i.street_id AS street_id',
                            's.name AS street_nome',
                            'c.id AS city_id',
                            'c.name AS city_nome',
                            'u.name AS user_nome',
                            'i.user_id AS user_id',
                            'i.cancellabile AS cancellabile',
                            'i.deleted_at AS deleted_at',
                            'i.created_at AS created_at',
                            'i.updated_at AS updated_at',
                        )
                        ->from('items AS i')
                        ->join('streets AS s', 'i.street_id', '=', 's.id')
                        ->join('cities AS c', 's.city_id', '=', 'c.id')
                        ->join('users AS u', 'i.user_id', '=', 'u.id')
                        ->leftJoin('item_tag AS it', 'i.id', '=', 'it.item_id');

                        foreach($types as $type) {
                            $tagType = strtolower($type);
                            $tagTypeColumn = $tagType . '_tag_id';

                            $itemsTag->leftJoin("tags AS $tagType", "$tagType.id", "=", "it.$tagTypeColumn");
                            $itemsTag->addSelect("$tagType.name AS $tagType");
                        }


        $selectedClient = request()->query('client');
        $selectedComune = request()->query('comune');

        $comuni = City::join('users', 'cities.user_id', '=', 'users.id')->where('users.id',  $selectedClient)->get();
        $streets = Street::join('cities', 'cities.id', '=', 'streets.city_id')->join('users', 'cities.user_id', '=', 'users.id')->where('users.id', $selectedComune)->select('streets.*')->get();
        $clients = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 3)->select('users.*')->get();
        $tagTypes = Tag::where('domain', 'item')->distinct('type_id')->pluck('type_id');
        $operators = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 2)->select('users.*')->get();
        $itemsDate = Item::pluck('time_stamp_pulizia');

        $tags = Tag::where('domain', 'item')->get();
        $groupedTags = [];

        $groupedTagsType = [];
        foreach ($tagTypes as $type) {
            $tags = Tag::where('domain', 'item')->where('type_id', $type)->get();
            $groupedTagsType[$type] = $tags;
        }


        return $dataTable->with('richiesta', $request->all())->render('pages.Items.index', compact('items', 'clients', 'operators', 'streets', 'comuni', 'tags', 'itemsDate', 'tagTypes', 'groupedTags', 'groupedTagsType', 'types'));
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

    public function createZipFileFromImg_Items()
    {

        $ret['success'] = false;
        $ret['data'] = [];

        $filteredItemsSession = Session::get('filteredItems');
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

    public function view(ItemsDataTable $dataTable, Item $item)
    {
        $types = TagType::pluck('name', 'id');
        $tagData = [];

        foreach($types as $typeId => $type) {
            $columnName = strtolower($type);

            $tags = Tag::where('type_id', $typeId)->get();
            $tagData[$typeId] = $tags;
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
    

        $itemDataFromView = DB::table('item_data_table_views')->where('id', $item->id)->first();

        return view('pages.Items.view', compact('itemDataFromView', 'prevItemId', 'nextItemId' ,'item', 'types', 'tagData', 'columnName'));
    }

    public function edit(ItemsDataTable $dataTable, Item $item)
    {

        $types = TagType::pluck('name', 'id');
        $tagData = [];

        foreach($types as $typeId => $type) {
            $columnName = strtolower($type) . '_tag_id';

            $tags = Tag::where('type_id', $typeId)->get();
            $tagData[$typeId] = $tags;
        }

        $items = Item::with('street', 'street.city', 'user')->orderBy('id', 'DESC')->get();

        $comuni = City::all();

        $strada_caditoia =  Street::find($item->street_id);

        $streets = Street::with('city')->where('city_id',$strada_caditoia->city_id)->get();
        $clients = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 3)->select('users.*')->get();
        $tagTypes = Tag::where('domain', 'item')->distinct('type_id')->pluck('type_id');
        $operators = User::join('role_user', 'users.id', '=', 'role_user.user_id')->join('roles', 'role_user.role_id', '=', 'roles.id')->where('roles.id', 2)->select('users.*')->get();
        $itemsDate = Item::pluck('time_stamp_pulizia');

        $currentTags = DB::table('item_tag')->where('item_id', $item->id)->first();

        $groupedTags = [];

        $groupedTagsType = [];
        foreach ($tagTypes as $type) {
            $tags = Tag::where('domain', 'item')->where('type_id', $type)->get();
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

        return view('pages.Items.edit', compact('currentTags', 'columnName', 'tagData', 'item', 'items', 'clients', 'operators', 'streets', 'comuni', 'tags', 'types', 'itemsDate', 'tagTypes', 'groupedTags', 'groupedTagsType', 'prevItemId', 'nextItemId'));
    }

    public function update(ItemsDataTable $dataTable, ItemRequest $request, Item $item)
    {
        $validated = $request->validated();
        
        // Associare la strada se esiste
        $street = Street::find($validated['street']);
        if ($street) {
            $item->street()->associate($street);
        }

        $tagsUpdate = [];
        $types = TagType::pluck('name', 'id');

        foreach ($types as $typeId => $type) {
            $tagTypeColumn = strtolower($type) . '_tag_id';
            $tagsUpdate[$tagTypeColumn] = $validated[$tagTypeColumn];
        }

        $itemTag = ItemTag::where('item_id', $item->id)->first();

        if($itemTag) {
            // Aggiornare la tabella item_tag se esiste
            $itemTag->update($tagsUpdate);
        } else {
            // Creare una nuova riga in item_tag se non esiste
            $tagsUpdate['item_id'] = $item->id; // assicurati di aggiungere l'item_id alla creazione
            ItemTag::create($tagsUpdate);
        }
        

        // Aggiornare la tabella item_tag
        // Usare Eloquent perchè va a prnendere i fillable e traduce null in set_null
        //ItemTag::where('item_id', $item->id)->update($tagsUpdate);
        // Con DB:: null lo riconosce come una stringa e non lo va ad inserire nel database
        //DB::table('item_tag')->where('item_id', $item->id)->update($tagsUpdate);

        // Aggiornare l'oggetto item
        $item->update($validated);

        return to_route('items.index', $item);
    }


    public function destroy($id)
    {
        return Item::find($id)->delete();
    }

    
}