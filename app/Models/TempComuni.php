<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempComuni extends Model
{
    use HasFactory;

    protected $table = 'temp_comuni';
    protected $primaryKey = 'id';
}