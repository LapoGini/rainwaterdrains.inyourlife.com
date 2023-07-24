<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comune extends Model
{
    protected $table = 'RWD_COMUNI';
    protected $primaryKey = 'comune_id';
    public $incrementing = false;
    protected $keyType = 'string';
}