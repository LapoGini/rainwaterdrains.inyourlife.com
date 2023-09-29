<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city_id', 'street_id_app'];

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }
}
