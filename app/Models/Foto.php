<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;

    protected $table = 'RWD_FOTO';

    public function caditoie()
    {
        return $this->belongsTo(Caditoie::class, 'tmp_caditoia_id', 'id');
    }

}