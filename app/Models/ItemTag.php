<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTag extends Model
{
    use HasFactory;

    protected $table = 'item_tag';
    
    protected $fillable = ['item_id', 'tag_id'];

    public function items() {
        return $this->belongsToMany(Item::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
    
}
