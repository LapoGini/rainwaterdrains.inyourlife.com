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
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->get();
        // TODO: caricare con ajax prima comuni, poi strade nel form di modifica
        $streets = Street::with('city')->get();
        $tags = Tag::where('domain', 'item')->get();

        $groupedTags = [];

        foreach ($items as $item) {
            $itemTags = $item->tags;


            ///// QUI PER VEDERE SE é NOTTURNO
            $timeStamp = $item->time_stamp_pulizia;
            $hour = (int) date('H', strtotime($timeStamp));
            $item->calcolo_notturno = ($hour >= 20 || $hour < 6) ? 'si' : 'no';

            //////////// QUI PER CREARE IL LINK ALLA FOTO
            $item->pic_link = $this->createLinkPathFromImg_Item($item);

            foreach ($itemTags as $tag) {
                $type = $tag->type;
                $groupedTags[$item->id][$type][] = $tag;
            }
        }

        return (new EloquentDataTable($query))
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
            ->editColumn('comune', function($item) {
                return $item->street->name;
            })
            ->editColumn('provincia', function($item) {
                return $item->street->city->name;
            })
            ->editColumn('civico', function($item) {
                return $item->civic ?? '';
            })
            ->editColumn('tipologia', function($item) {
                return 'tipologia';
            })
            ->editColumn('stato', function($item) {
                return 'stato';
            })
            ->editColumn('height', function($item) {
                return $item->height;
            })
            ->editColumn('width', function($item) {
                return $item->width;
            })
            ->editColumn('depth', function($item) {
                return $item->depth;
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
                return 'recapito';
            })
            ->editColumn('data_pulizia', function($item) {
                return $item->time_stamp_pulizia;
            })
            ->editColumn('latitudine', function($item) {
                return $item->latitude;
            })
            ->editColumn('longitudine', function($item) {
                return $item->longitude;
            })
            ->editColumn('altitudine', function($item) {
                return $item->altitude;
            })
            ->editColumn('operatore', function($item) {
                return $item->user->name;
            })
            ->editColumn('solo_georef', function($item) {
                return 'solo georef';
            })
            ->editColumn('eseguire_a_mano_in_notturno', function($item) {
                return $item->calcolo_notturno;
            })
            ->editColumn('link_fotografia', function($item) {
                return $item->pic_link;
            })
            ->editColumn('note', function($item) {
                return $item->note;
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
        $query = $model->newQuery();

        $clientId = $this->request()->input('clientId');
        $comuneId = $this->request()->input('comuneId');
        $streetId = $this->request()->input('streetId');
        $fromDateId = $this->request()->input('fromDateId');
        $toDateId = $this->request()->input('toDateId');
        $operatorId = $this->request()->input('operatorId');
        $selectedTags = $this->request()->input('tags');

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
                    ->orderBy(0)
                    ->selectStyleSingle()
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
                                    /*'columnDefs' => [
                                        ['visible' => false, 'targets' => [2, 5, 6, 7, 8, 9, 10, 13, 14, 15, 17, 18, 19]],
                                    ],*/
                                    'initComplete' => 'function() {
                                        hideDownloadButtons();
                                        hideDeletableButton();
                                        hideZipButton();
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
        $clientId = $this->request()->input('clientId');
        $comuneId = $this->request()->input('comuneId');
        $streetId = $this->request()->input('streetId');
        $fromDateId = $this->request()->input('fromDateId');
        $toDateId = $this->request()->input('toDateId');
        $operatorId = $this->request()->input('operatorId');
        $selectedTags = $this->request()->input('tags');

        $query = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC');

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

        $items = $query->get();

        return [
            Column::make('id'),

            // mie colonne
            Column::make('comune')->name('comune'),
            Column::make('provincia')->name('provincia'),
            Column::make('civic')->name('civico'),
            Column::make('tipologia')->name('tipologia'),
            Column::make('stato')->name('stato'),
            Column::make('height')->name('lunghezza'),
            Column::make('depth')->name('larghezza'),
            Column::make('depth')->name('profondità'),
            Column::make('volume')->name('volume'),
            Column::make('area')->name('area'),
            Column::make('caditoie_equiv')->name('caditoie_equiv'),
            Column::make('recapito')->name('recapito'),
            Column::make('data_pulizia')->name('data_pulizia'),
            Column::make('latitudine')->name('latitudine'),
            Column::make('longitudine')->name('longitudine'),
            Column::make('altitudine')->name('altitudine'),
            Column::make('operatore')->name('operatore'),
            Column::make('solo_georef')->name('solo_georef'),
            Column::make('eseguire_a_mano_in_notturno')->name('eseguire_a_mano_in_notturno'),
            Column::make('link_fotografia')->name('link_fotografia'),
            Column::make('note')->name('note'),

            Column::computed('action')
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

    public function createLinkPathFromImg_Item($item) {
        $linkPath = env('APP_URL') . '/img_items/' . date('Ymd', strtotime($item->time_stamp_pulizia)) . '/' . $item->pic;
        return $linkPath;
    }
}
