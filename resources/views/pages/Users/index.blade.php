@extends('layouts.app')
@section('content')

<div class="mx-5 pb-5 relative bg-white overflow-x-auto">
    <h2 class="py-3">
        Utenti
    </h2>
    <a href="{{ route('users.create') }}" class="d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-black text-light border-0 py-2 px-3">
        AGGIUNGI <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus ms-2 svg-plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <path fill="currentColor" d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"></path>
        </svg>
    </a>
    
    <table id="zanetti-table" class="table table-hover w-100 text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Nome
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
                <th scope="col" class="px-6 py-3">
                    Ruolo
                </th>
                <th scope="col" class="px-6 py-3">
                    Azioni
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key=>$user)

            <tr class="border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$user->id}}
                </th>
                <td class="px-6 py-4">
                    {{$user->name}}
                </td>
                <td class="px-6 py-4">
                    {{$user->email}}
                </td>
                <td class="px-6 py-4">
                @foreach ($user->roles as $role)
                    <small>{{ $role->name }}</small>
                @endforeach
                </td>
                <td class="px-6 py-4">
                    <div class="flex-none">
                        <a class="px-3 py-2 rounded me-3 bg-black text-white" href="{{ route('users.edit', $user) }}">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        <a class="px-3 py-2 rounded bg-danger text-white" href="{{ route('users.destroy', $user) }}" onclick="event.preventDefault(); if (confirm('Sei sicuro di voler eliminare questo utente?')) { document.getElementById('delete-form').submit(); }">
                            <i class="fa-solid fa-trash"></i>
                        </a>

                        <form id="delete-form" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@endsection