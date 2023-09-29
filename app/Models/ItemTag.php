<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ItemTag extends Model
{
    use HasFactory;

    protected $table = 'item_tag';
    protected $fillable = [];

    public function __construct()
    {
        $this->setFillable();
    }

    private function setFillable()
    {
        $fields = Schema::getColumnListing($this->table);
        $this->fillable = $fields;
    }

    public function items() {
        return $this->belongsToMany(Item::class);
    }
}

// rimossa colonna tags_id e rimossa relazione belongsToMany con i tags
// aggiunte tre colonne distinte, ognuna rappresentativa di un tipo di tag
// definita la relazione belongsTo dove ad ogni tag appartiene una propria tipologia