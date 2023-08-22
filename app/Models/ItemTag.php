<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    use HasFactory;

    protected $table = 'item_tag';
    
    protected $fillable = ['item_id', 'recapito_tag_id', 'stato_tag_id', 'tipologia_tag_id'];

    public function items() {
        return $this->belongsToMany(Item::class);
    }

    public function recapitoTag() {
        return $this->belongsTo(Tag::class, 'recapito_tag_id');
    }

    public function statoTag() {
        return $this->belongsTo(Tag::class, 'stato_tag_id');
    }

    public function tipologiaTag() {
        return $this->belongsTo(Tag::class, 'tipologia_tag_id');
    }
    
}

// rimossa colonna tags_id e rimossa relazione belongsToMany con i tags
// aggiunte tre colonne distinte, ognuna rappresentativa di un tipo di tag
// definita la relazione belongsTo dove ad ogni tag appartiene una propria tipologia