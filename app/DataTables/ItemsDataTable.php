<?php

namespace App\DataTables;

use App\Models\Item;
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
use Yajra\DataTables\Datatables;

class ItemsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        $searchValue = $this->request->input('search.value');

        return (new EloquentDataTable($query, $searchValue))
            ->addColumn('action', function($item) {
                $actionBtn = 
                '<a href="'.route('items.edit', $item).'" class="px-3 py-2 rounded me-3 bg-black text-white"><i class="fas fa-pen-to-square"></i></a>
                <a href="'.route('items.destroy', $item).'" class="px-3 py-2 rounded bg-danger text-white" onclick="event.preventDefault(); if (confirm(\'Sei sicuro di voler eliminare questo comune?\')) { document.getElementById(\'delete-form-' . $item .'\').submit(); }">
                    <i class="fa-solid fa-trash"></i>
                </a>
                <form id="delete-form-' . $item . '" action="'.route('items.destroy', $item).'" method="POST" style="display: none;">
                    @csrf
                    @method(\'DELETE\')
                </form>';

                return $actionBtn;
            })
            ->editColumn('tipologia', function($item) {
                $results = DB::select("
                    SELECT tags.name
                    FROM tags
                    JOIN item_tag ON tags.id = item_tag.tag_id
                    WHERE tags.type = 'Tipo Pozzetto'
                    AND item_tag.item_id = $item->id
                ");

                return !empty($results) ? $results[0]->name : '';
            })
            ->orderColumn('tipologia', function ($query, $order) {
                $query->select('tags.name as tipologia')
                    ->from('tags')
                    ->join('item_tag', 'tags.id', '=', 'item_tag.tag_id')
                    ->join('items', 'item_tag.item_id', '=', 'items.id')
                    ->where('tags.type', 'Tipo Pozzetto')
                    ->orderBy('tipologia', $order);
            })
            ->editColumn('stato', function($item) {
                $tipologia_nome = null;
                foreach ($item->tags()->get() as $tag) {
                    if ($tag->type == 'Stato') {
                        $tipologia_nome = $tag->name;
                        break;
                    }
                }
                return $tipologia_nome;
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
            ->editColumn('recapito', function($item) {
                $tipologia_nome = null;
                foreach ($item->tags()->get() as $tag) {
                    if ($tag->type == 'Recapito') {
                        $tipologia_nome = $tag->name;
                        break;
                    }
                }
                return $tipologia_nome;
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
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Item $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Item $model): QueryBuilder
    {

        $query = $model->newQuery()->with(['street', 'street.city', 'tags', 'user']);

        $searchValue = $this->request->input('search.value');

        //dd($searchValue);


        $clientId = (isset($this->richiesta['client']) ? $this->richiesta['client'] : '');
        $comuneId = (isset($this->richiesta['comune']) ? $this->richiesta['comune'] : '');
        $streetId = (isset($this->richiesta['street']) ? $this->richiesta['street'] : '');
        $fromDateId = (isset($this->richiesta['fromDate']) ? $this->richiesta['fromDate'] : '');
        $toDateId = (isset($this->richiesta['toDate']) ? $this->richiesta['toDate'] : '');
        $operatorId = (isset($this->richiesta['operator']) ? $this->richiesta['operator'] : '');
        $selectedTags = (isset($this->richiesta['tags']) ? $this->richiesta['tags'] : '');

        /*if ($searchValue) {
            $results = DB::select("
                SELECT streets.name, cities.name, tags.name, items.time_stamp_pulizia, roles.name, items.note
                FROM items
                JOIN streets ON items.street_id = streets.id
                JOIN cities ON streets.city_id = cities.id
                JOIN item_tag ON items.id = item_tag.item_id
                JOIN tags ON item_tag.tag_id = tags.id
                JOIN users ON items.user_id = users.id
                JOIN role_user ON users.id = role_user.user_id
                JOIN roles ON role_user.role_id = roles.id
                WHERE (streets.name LIKE '%" . $searchValue . "%' OR cities.name LIKE '%" . $searchValue . "%' OR tags.name LIKE '%" . $searchValue . "%' OR items.time_stamp_pulizia LIKE '%" . $searchValue . "%' OR roles.name LIKE '%" . $searchValue . "%' OR items.note LIKE '%" . $searchValue . "%')
                AND items.street_id = streets.id
            ");
        }*/
        
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

        if ($selectedTags) {
            $query->whereHas('tags', function ($query) use ($selectedTags) {
                $query->whereIn('tags.id', $selectedTags);
            });
        }

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
                                    'processing' => true,
                                    'language' => [
                                        'url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/it-IT.json',
                                    ],
                                    'buttons' => [
                                        ['extend' => 'csv', 'text' => 'DOWNLOAD CSV'],
                                        ['extend' => 'excel', 'text' => 'DOWNLOAD XLSX'],
                                    ],
                                    'columnDefs' => [
                                        ['visible' => false, 'targets' => [0, 3, 6, 7, 8, 9, 10, 11, 14, 15, 16, 18, 19, 20]],
                                        ['searchable' => false, 'targets' => [0, 3, 6, 7, 8, 9, 10, 11, 14, 15, 16, 18, 19, 20]],

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
    
        return [
            Column::make('id')
                    ->searchable(false),

            // mie colonne
            Column::make('street.name')->title('Via'),
            Column::make('street.city.name')->title('Provincia'),
            Column::make('civic')->title('Civico')
                    ->searchable(false),
            Column::make('tipologia')->title('Tipologia'),
            Column::make('stato')->title('Stato'),
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
            Column::make('recapito')->title('Recapito'),
            Column::make('time_stamp_pulizia')->title('Data Pulizia'),
            Column::make('latitude')->title('Latitudine')
                    ->searchable(false),
            Column::make('longitude')->title('Longitudine')
                    ->searchable(false),
            Column::make('altitude')->title('Altitudine')
                    ->searchable(false),
            Column::make('user.name')->title('Operatore'),
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
        ];;
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
