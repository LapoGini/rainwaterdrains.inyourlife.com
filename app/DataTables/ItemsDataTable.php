<?php

namespace App\DataTables;

use App\Models\ItemDataTableView;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use App\Models\TagType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ItemsDataTable extends DataTable
{
    public $filteredItems = [];

    protected $allTagTypes;

    public function __construct()
    {
        parent::__construct();
        $this->allTagTypes = DB::table('tags')->distinct()->pluck('type_id')->toArray();
    }

    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        $searchValue = $this->request->input('search.value');

        $dataTable = (new EloquentDataTable($query, $searchValue))
            ->addColumn('action', function($item) {
                $editUrl = url('items/' . $item->id . '/edit');
                $viewUrl = url('items/view/' . $item->id );
                $deleteUrl = route('items.destroy', ['id' => $item->id]);

                $actionBtn =
                '
                <a onclick="openwindow(1000,1500,\'' . $viewUrl . '\')" class="btn btn-success p-1"><i class="fas fa-search"></i> Vedi</a>
                <a onclick="openwindow(1000,1500,\'' . $editUrl . '\')" class="btn btn-primary p-1"><i class="fas fa-edit"></i> Modifica</a>
                <a 
                    class="btn btn-danger delete_item p-1"
                    title="Cancella"
                    data-url="' . $deleteUrl . '"
                    onclick="event.preventDefault(); if(confirm(\'Sei sicuro di voler eliminare questo comune?\')){ destroy(this.getAttribute(\'data-url\')) }"
                    href="javascript:void(0)"
                >
                    <i class="fa-solid fa-trash"></i> Cancella
                </a>

                ';

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
    public function query(ItemDataTableView $model): QueryBuilder
    {

        $searchValue = $this->request->input('search.value');

        //$itemDataTableView = new ItemDataTableView();
        //$itemDataTableView->itemDataTableViewQuery();

        $query = $model->newQuery();

        

        $clientId = (isset($this->richiesta['client']) ? $this->richiesta['client'] : '');
        $comuneId = (isset($this->richiesta['comune']) ? $this->richiesta['comune'] : '');
        $streetId = (isset($this->richiesta['street']) ? $this->richiesta['street'] : '');
        $fromDateId = (isset($this->richiesta['fromDate']) ? $this->richiesta['fromDate'] : '');
        $toDateId = (isset($this->richiesta['toDate']) ? $this->richiesta['toDate'] : '');
        $operatorId = (isset($this->richiesta['operator']) ? $this->richiesta['operator'] : '');
        $selectedTags = (isset($this->richiesta['tags']) ? $this->richiesta['tags'] : '');

        if ($clientId) {
            $query->whereHas('street.city', function ($query) use ($clientId) {
                $query->where('user_id', $clientId);
            });
        }

        if ($comuneId) {
            $query->whereHas('street.city', function ($query) use ($comuneId) {
                $query->where('id', $comuneId);
            });
        }

        if ($streetId) {
            $query->whereHas('street', function ($query) use ($streetId) {
                $query->where('id', $streetId);
            });
        }

        if ($fromDateId && $toDateId) {
            $fromDateId = new Carbon($fromDateId);
            $toDateId = new Carbon($toDateId);
            $query->whereBetween('time_stamp_pulizia', [$fromDateId->startOfDay(), $toDateId->endOfDay()]);
        }

        if ($operatorId) {
            $query->whereHas('user', function ($query) use ($operatorId) {
                $query->where('id', $operatorId);
            });
        }

        if($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('street_nome', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('city_nome', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('time_stamp_pulizia', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('user_nome', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('note', 'LIKE', '%' . $searchValue . '%');

                    $tagType = DB::table('tag_types')->pluck('name');
                    foreach($tagType as $tag) {
                        $tagTypeStr = strtolower($tag);
                        $query->orWhere($tagTypeStr, 'like', '%' . $searchValue . '%');
                    }
            });
        }

        if ($selectedTags) {
            foreach ($selectedTags as $tagTypeId => $tags) {
                $tagType = DB::table('tag_types')->where('id', $tagTypeId)->value('name');
                $tagType = strtolower($tagType) . '_id';
                foreach ($tags as $tagId) {
                    $query->where($tagType, $tagId);
                }
            }
        }

        $this->filteredItems = $query->pluck('id')->toArray();
        Session::put('filteredItems', $this->filteredItems);
        return $query;
    }

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
                                    'pageLength' => 50,
                                    'lengthMenu' => [
                                        [50, 250, 1000, -1],
                                        [50, 250, 1000, 'All']
                                    ],
                                    'language' => [
                                        'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/it-IT.json',
                                    ],
                                    'buttons' => [
                                        ['extend' => 'csv', 'text' => 'DOWNLOAD CSV','exportOptions' => ['columns' => 'th:not(:last-child)']],
                                        ['extend' => 'excel', 'text' => 'DOWNLOAD XLSX','exportOptions' => ['columns' => 'th:not(:last-child)']],
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
                ->searchable(false)
                ->visible(false),
        ];

        $types = TagType::pluck('name', 'id');

        foreach($types as $type) {
            $nameType = strtolower($type);

            array_push($tagsArray, Column::make($nameType)->title($type));
        }

        return array_merge($tagsArray,
        [

            Column::make('height')->title('Lunghezza')
                    ->searchable(false)
                    ->visible(false),
            Column::make('width')->title('Larghezza')
                    ->searchable(false)
                    ->visible(false),
            Column::make('depth')->title('Profondità')
                    ->searchable(false)
                    ->visible(false),
            Column::make('volume')->title('Volume')
                    ->searchable(false)
                    ->visible(false),
            Column::make('area')->title('Area')
                    ->searchable(false)
                    ->visible(false),
            Column::make('caditoie_equiv')->title('Caditoie_equiv')
                    ->searchable(false)
                    ->visible(false),
            Column::make('time_stamp_pulizia')->title('Data Pulizia'),
            Column::make('latitude')->title('Latitudine')
                    ->searchable(false)
                    ->visible(false),
            Column::make('longitude')->title('Longitudine')
                    ->searchable(false)
                    ->visible(false),
            Column::make('altitude')->title('Altitudine')
                    ->searchable(false)
                    ->visible(false),
            Column::make('user_nome')->title('Operatore'),
            Column::make('solo_georef')->title('Solo_georef')
                    ->searchable(false)
                    ->visible(false),
            Column::make('eseguire_a_mano_in_notturno')->title('Eseguire_a_mano_in_notturno')
                    ->searchable(false)
                    ->visible(false),
            Column::make('link_fotografia')->title('link_foto')
                    ->searchable(false)
                    ->visible(false),
            Column::make('note')->title('note'),

            Column::computed('action')
                    ->searchable(false)
                    ->exportable(false)
                    ->printable(false)
                    ->width(300)
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
