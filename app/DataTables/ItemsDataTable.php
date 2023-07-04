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

        return (new EloquentDataTable($query))
            ->addColumn('action', 'items.action')
            ->addColumn('address', function ($item) {
                return $item->street->name . ', ' . $item->street->city->name;
            })
            ->addColumn('size', function ($item) {
                return round($item->height) . 'L x ' . round($item->width) . 'S x ' . round($item->depth) . 'P';
            })
            ->addColumn('tags', function ($item) use ($groupedTags) {
                $html = '';
    
                if (isset($groupedTags[$item->id])) {
                    foreach ($groupedTags[$item->id] as $type => $tags) {
                        $html .= '<p>';
                        $html .= '<small class="font-bold mr-1">' . $type . ':</small>';
                        
                        foreach ($tags as $tag) {
                            $html .= '<span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">' . $tag->name . '</span>';
                        }
                        $html .= '</p>';
                    }
                }
                return $html;
            })
            ->addColumn('operator', function($item) {
                return $item->user->name;
            })
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
                    ->setTableId('items-table')
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
            Column::make('address')->name('Indirizzo'),
            Column::make('size')->name('Dimensioni'),
            Column::make('characteristics')->name('Caratteristiche'),
            Column::make('operator')->name('Operatore'),

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
