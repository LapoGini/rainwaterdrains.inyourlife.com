<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['latitude', 'longitude', 'altitude', 'accuracy','height', 'width', 'depth', 'pic', 'note'];

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
