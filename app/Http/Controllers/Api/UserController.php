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
        $users = User::with('roles')->orderBy('id', 'DESC')->get();
        
        return Functions::setResponse($users, 'Utenti non trovati');
    }

    public function getById(int $id) 
    {
        $user = User::with('roles')->find($id);

        return Functions::setResponse($user, 'Utente non trovato');
    }

    public function getByRole(string $role) 
    {
        $users = User::with('roles')->whereHas('roles', function (Builder $query) use ($role) {
            $query->where('slug', '=', $role);
        })->get();
        
        if ($role=='cliente') {
            foreach ($users as $user){
                $user->abilita_modulo_su_app=1; //default per compatibilità con il vecchio
            }
        }

        return Functions::setResponse($users, 'Utenti non trovati');
    }

}
