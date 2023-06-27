<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Utils\Functions;

class UserController extends Controller
{

    public function getAll() 
    {
        $users = User::with('roles', 'cities', 'items')->orderBy('id', 'DESC')->get();
        
        return Functions::setResponse($users, 'Utenti non trovati');
    }

    public function getById(int $id) 
    {
        $user = User::with('roles', 'cities', 'items')->find($id);

        return Functions::setResponse($user, 'Utente non trovato');
    }

    public function getByRole(string $role) 
    {
        $users = User::with('roles', 'cities', 'items')->whereHas('roles', function (Builder $query) use ($role) {
            $query->where('slug', '=', $role);
        })->get();

        return Functions::setResponse($users, 'Utenti non trovati');
    }

}
