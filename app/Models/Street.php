<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city_id', 'street_id_app', 'comune_id_vecchio_db', 'strada_id_vecchio_db'];

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }
}
