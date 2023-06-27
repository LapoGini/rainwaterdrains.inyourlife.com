<?php

namespace App\Http\Controllers\Sync;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use jeremykenedy\LaravelRoles\Models\Role;

use Illuminate\Support\Arr;

class SyncController extends Controller
{
    public function index() 
    {
        return 'test per sync';
    }
}
