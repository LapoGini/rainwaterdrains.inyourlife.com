<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caditoie extends Model
{
    use HasFactory;

    protected $table = 'RWD_CADITOIE';

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
