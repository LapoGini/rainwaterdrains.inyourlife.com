<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use jeremykenedy\LaravelRoles\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() 
    {
        $users = User::with('roles')->orderBy('id', 'DESC')->get();
        $roles = Role::get();

        return view('pages.Users.index' , compact('users', 'roles'));
    }

    public function create()
    {
        return view('pages.Users.create');
    }

    public function store(StoreUserRequest $request) : RedirectResponse
    {
        $validated = $request->validated();

        // Cripta la password utilizzando bcrypt
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if (isset($validated['rolesIds'])){
            $user->roles()->sync($validated['rolesIds']);
        }
        return to_route('users.index');
    }

    public function edit(User $user)
    {
        return view('pages.Users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user) : RedirectResponse
    {
        //$this->authorize('update', $user);
        $validated = $request->validated();
        
        $exceptArr = [];
        if(!$validated['password']||!$validated['password']=="") {
            $exceptArr[] = 'password';
            $user->password = Hash::make($validated['password']);
        }
        $user->update(Arr::except($validated, $exceptArr));

        if(isset($validated['rolesIds'])){
            $user->roles()->sync($validated['rolesIds']);
        }
        return to_route('users.index');
    }

    public function destroy(User $user) : RedirectResponse
    {
        //$this->authorize('delete', $user);
        $user->delete();
        return to_route('users.index');
    }
}
