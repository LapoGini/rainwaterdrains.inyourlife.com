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
use Yajra\DataTables\Services\DataTable;
use App\Models\Street;
use App\Models\Tag;

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
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->paginate(50);
        // TODO: caricare con ajax prima comuni, poi strade nel form di modifica
        $streets = Street::with('city')->get();
        $tags = Tag::where('domain', 'item')->get();

        $groupedTags = [];

        foreach ($items as $item) {
            $itemTags = $item->tags;

            foreach ($itemTags as $tag) {
                $type = $tag->type;
                $groupedTags[$item->id][$type][] = $tag;
            }
        }

        /*return (new EloquentDataTable($query))
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
            ->rawColumns(['action'])
            ->setRowId('id');*/
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Item $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Item $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('items-table') //inserire id tabella items (zanetti-table-download)
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->paginate(50);
        // TODO: caricare con ajax prima comuni, poi strade nel form di modifica
        $streets = Street::with('city')->get();
        $tags = Tag::where('domain', 'item')->get();

        $groupedTags = [];

        foreach ($items as $item) {
            $itemTags = $item->tags;

            foreach ($itemTags as $tag) {
                $type = $tag->type;
                $groupedTags[$item->id][$type][] = $tag;
            }
        }

        return [
            Column::make('id'),

            // mie colonne
            Column::make('comune')->name('comune'),
            Column::make('provincia')->name('provincia'),
            Column::make('civic')->name('civico'),
            Column::make('tipologia')->name('tipologia'),
            Column::make('stato')->name('stato'),
            Column::make('lunghezza')->name('lunghezza'),
            Column::make('larghezza')->name('larghezza'),
            Column::make('profondità')->name('profondità'),
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
            Column::make('azioni')->name('tipologia'),

            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
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
}
