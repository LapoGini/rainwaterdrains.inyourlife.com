<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'domain'];


    public function items() {
        return $this->belongsToMany(Item::class);
    }

    public function tagType() {
        return $this->belongsTo(TagType::class, 'type_id');
    }
}

// rimossa la colonna type ed aggiunta la relazione belongsTo con i TagType
// (un tag può avere un solo tagType)
// es. Fognatura Bianca può essere solo Recapito
// es. Radici può essere solo stato
