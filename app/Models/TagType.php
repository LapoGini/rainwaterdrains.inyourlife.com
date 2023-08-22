<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function tags() {
        return $this->hasMany(Tag::class, 'type_id');
    }
}

// creato il modello TagType
// relazione con i Tags di tipo hasMany (un tipo di tag può essere associato a più tag)
// es. Recapito può essere associato a più tag (Fognatura Bianca, Fognatura Nera...)