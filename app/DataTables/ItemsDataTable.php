<?php

namespace App\DataTables;

use App\Models\ItemDataTableView;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use App\Models\Street;
use App\Models\Tag;
use App\Models\City;
use App\Models\User;
use App\Http\Requests\FilteredDataRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Datatables;


class ItemsDataTable extends DataTable
{

    public $filteredItems = [];

    protected $allTagTypes;

    public function __construct()
    {
        parent::__construct();
        $this->allTagTypes = DB::table('tags')->distinct()->pluck('type')->toArray();
    }

    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable()
    {
        $searchValue = $this->request->input('search.value');

        $allTagTypes = DB::table('tags')->distinct()->pluck('type')->toArray();

        $query = DB::table('jc6n141b_zanetti_dev.items AS i')
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
            'i.user_id AS user_id',
            'i.cancellabile AS cancellabile',
            'i.deleted_at AS deleted_at',
            'i.created_at AS created_at',
            'i.updated_at AS updated_at'
        )
        ->selectRaw('s.name AS street_nome')
        ->selectRaw('c.id AS city_id')
        ->selectRaw('c.name AS city_nome')
        ->selectRaw('u.name AS user_nome')
        ->join('streets AS s', 'i.street_id', '=', 's.id')
        ->join('cities AS c', 's.city_id', '=', 'c.id')
        ->join('users AS u', 'i.user_id', '=', 'u.id');

        // sottoquery dinamiche per i nomi
        foreach ($allTagTypes as $tagType) {
            $query->selectRaw("(
            SELECT GROUP_CONCAT(`tags`.`name` SEPARATOR ',')
            FROM `tags`
            JOIN `item_tag` ON `tags`.`id` = `item_tag`.`tag_id`
            WHERE `tags`.`type` = '$tagType' AND `item_tag`.`item_id` = `i`.`id`
            ) AS $tagType");
        }

        // sottoquery dinamiche per gli id
        foreach ($allTagTypes as $tagType) {
            $tagTypeString = $tagType . '_id';
            $query->selectRaw("(
            SELECT GROUP_CONCAT(`tags`.`id` SEPARATOR ',')
            FROM `tags`
            JOIN `item_tag` ON `tags`.`id` = `item_tag`.`tag_id`
            WHERE `tags`.`type` = '$tagType' AND `item_tag`.`item_id` = `i`.`id`
            ) AS $tagTypeString");
        }

        $clientId = (isset($this->richiesta['client']) ? $this->richiesta['client'] : '');
        $comuneId = (isset($this->richiesta['comune']) ? $this->richiesta['comune'] : '');
        $streetId = (isset($this->richiesta['street']) ? $this->richiesta['street'] : '');
        $fromDateId = (isset($this->richiesta['fromDate']) ? $this->richiesta['fromDate'] : '');
        $toDateId = (isset($this->richiesta['toDate']) ? $this->richiesta['toDate'] : '');
        $operatorId = (isset($this->richiesta['operator']) ? $this->richiesta['operator'] : '');
        $selectedTags = (isset($this->richiesta['tags']) ? $this->richiesta['tags'] : '');
        
        if ($clientId) {
            $query->where('user_id', $clientId);
        }

        if ($comuneId) {
            $query->where('city_id', $comuneId);
        }

        if ($streetId) {
            $query->where('street_id', $streetId);
        }

        if ($fromDateId && $toDateId) {
            $fromDateId = new Carbon($fromDateId);
            $toDateId = new Carbon($toDateId);
            $query->whereBetween('time_stamp_pulizia', [$fromDateId->startOfDay(), $toDateId->endOfDay()]);
        }

        if ($operatorId) {
            $query->where('user_id', $operatorId);
        }

        $allTagTypes = DB::table('tags')->distinct()->pluck('type')->toArray();
        
        if($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('street_nome', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('city_nome', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('recapito', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('tipologia', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('stato', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('time_stamp_pulizia', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('user_nome', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('note', 'LIKE', '%' . $searchValue . '%');
            });
        }

        if ($selectedTags) {
            foreach($allTagTypes as $tagType) {
                $tagTypeString = $tagType . '_id';
                $query->orWhereIn($tagTypeString, $selectedTags);
            }
        }

        $this->filteredItems = $query->pluck('id')->toArray();
        Session::put('filteredItems', $this->filteredItems);

        $dataTable = DataTables::of($query, $searchValue)
            ->addColumn('action', function($item) {
                $deleteUrl = url('items/' . $item->id);
            
                $actionBtn =
                '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="{\'id\': ' . $item->id . ', \'state\': \'view\'}">View</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="{\'id\': ' . $item->id . ', \'state\': \'edit\'}">Edit</button>
                <a href="' . $deleteUrl . '" class="px-3 py-2 rounded bg-danger text-white" onclick="event.preventDefault(); if (confirm(\'Sei sicuro di voler eliminare questo comune?\')) { document.getElementById(\'delete-form-' . $item->id .'\').submit(); }">
                    <i class="fa-solid fa-trash"></i>
                </a>
                <form id="delete-form-' . $item->id . '" action="' . $deleteUrl . '" method="POST" style="display: none;">
                    @csrf
                    @method(\'DELETE\')
                </form>';
            
                return $actionBtn;
            })
            ->editColumn('volume', function($item) {
                return $item->height * $item->width * $item->depth;
            })
            ->editColumn('area', function($item) {
                return $item->width * $item->depth;
            })
            ->editColumn('caditoie_equiv', function($item) {
                return 'caditoie equiv.';
            })
            
            ->editColumn('solo_georef', function($item) {
                return 'solo georef';
            })
            ->editColumn('eseguire_a_mano_in_notturno', function($item) {
                $timeStamp = $item->time_stamp_pulizia;
                $hour = (int) date('H', strtotime($timeStamp));
                $item->calcolo_notturno = ($hour >= 20 || $hour < 6) ? 'si' : 'no';
                return $item->calcolo_notturno;
            })
            ->editColumn('link_fotografia', function($item) {
                $item->pic_link = $this->createLinkPathFromImg_Item($item);
                return $item->pic_link;
            })
            ->filterColumn('search_value', function ($query, $searchValue) {
                $query->where(function ($query) use ($searchValue) {
                    $query->where('street_nome', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('city_nome', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('recapito', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('tipologia', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('stato', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('time_stamp_pulizia', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('user_nome', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('note', 'LIKE', '%' . $searchValue . '%');
                });
            })
            ->rawColumns(['action'])
            ->setRowId('id');

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Item $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    /*public function query(ItemDataTableView $model)
    {
       
    }*/

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        
        return $this->builder()
                    ->setTableId('zanetti-table-download')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfltip')
                    ->parameters([
                                    'serverSide' => true,
                                    'bprocessing' => true,
                                    'deferRender' => true,
                                    'pageLength' => 10,
                                    'language' => [
                                        'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/it-IT.json',
                                    ],
                                    'buttons' => [
                                        ['extend' => 'csv', 'text' => 'DOWNLOAD CSV'],
                                        ['extend' => 'excel', 'text' => 'DOWNLOAD XLSX'],
                                    ],
                                    'columnDefs' => [
                                        ['visible' => false, 'targets' => [0, 3, 7, 8, 9, 10, 11, 12, 14, 15, 16, 18, 19, 20]],
                                    ],
                                    'initComplete' => 'function() {
                                        $(".buttons-csv, .buttons-excel").hide();
                                    }',
                                ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        $tagsArray = [Column::make('id')    
        ->searchable(false),
        // mie colonne'items.id'
        Column::make('street_nome')->title('Via'),
        Column::make('city_nome')->title('Provincia'), 
        Column::make('civic')->title('Civico')
                ->searchable(false),
        ];

        foreach($this->allTagTypes as $TagType) {
            $tagTypeTitle = ucfirst($TagType);

            array_push($tagsArray, Column::make($TagType)->title($tagTypeTitle));
        }

        return array_merge($tagsArray, 
        [
           
            Column::make('height')->title('Lunghezza')
                    ->searchable(false),
            Column::make('width')->title('Larghezza')
                    ->searchable(false),
            Column::make('depth')->title('Profondità')
                    ->searchable(false),
            Column::make('volume')->title('Volume')
                    ->searchable(false),
            Column::make('area')->title('Area')
                    ->searchable(false),
            Column::make('caditoie_equiv')->title('Caditoie_equiv')
                    ->searchable(false),
            Column::make('time_stamp_pulizia')->title('Data Pulizia'),
            Column::make('latitude')->title('Latitudine')
                    ->searchable(false),
            Column::make('longitude')->title('Longitudine')
                    ->searchable(false),
            Column::make('altitude')->title('Altitudine')
                    ->searchable(false),
            Column::make('user_nome')->title('Operatore'),
            Column::make('solo_georef')->title('Solo_georef')
                    ->searchable(false),
            Column::make('eseguire_a_mano_in_notturno')->title('Eseguire_a_mano_in_notturno')
                    ->searchable(false),
            Column::make('link_fotografia')->title('link_foto')
                    ->searchable(false),
            Column::make('note')->title('note'),

            Column::computed('action')
                  ->searchable(false)
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ]);
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Items_' . date('YmdHis');
    }

    public function createLinkPathFromImg_Item($item) 
    {
        $linkPath = env('APP_URL') . '/img_items/' . date('Ymd', strtotime($item->time_stamp_pulizia)) . '/' . $item->pic;
        return $linkPath;
    }
}
