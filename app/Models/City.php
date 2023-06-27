<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'district', 'user_id', 'pics'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function streets() {
        return $this->hasMany(Street::class);
    }
    
}
