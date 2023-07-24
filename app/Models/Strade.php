<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strade extends Model
{
    use HasFactory;

    protected $table = 'RWD_STRADE';

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }
}
