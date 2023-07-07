<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_sd', 
        'id_da_app',
        'time_stamp_pulizia',
        'civic',
        'caditoie_equiv',
        'latitude', 
        'longitude', 
        'altitude', 
        'accuracy',
        'height', 
        'width', 
        'depth', 
        'pic', 
        'note'];

    public function street() {
        return $this->belongsTo(Street::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}
