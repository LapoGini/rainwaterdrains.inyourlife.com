@extends('layouts.app')
@section('content')
<div class="mx-5 pb-5 relative bg-white overflow-x-auto">
    <h2 class="py-3">
        Comuni
    </h2>
    <a href="{{ route('cities.create') }}" class="d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-black text-light border-0 py-2 px-3">
        AGGIUNGI <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus ms-2 svg-plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <path fill="currentColor" d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"></path>
        </svg>
    </a>

    <table id="zanetti-table" class="table table-striped table-hover w-100 text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Comune
                </th>
                <th scope="col" class="px-6 py-3">
                    Provincia
                </th>
                <th scope="col" class="px-6 py-3">
                    Richiede IMG
                </th>
                <th scope="col" class="px-6 py-3">
                    Cliente
                </th>
                <th scope="col" class="px-6 py-3">
                    Azioni
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($cities as $key=>$city)
            <tr class="border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$city->id}}
                </th>
                <td class="px-6 py-4">
                    {{$city->name}}
                </td>
                <td class="px-6 py-4">
                    {{$city->district}}
                </td>
                <td class="px-6 py-4">
                    {{ $city->pics }}
                </td>
                <td class="px-6 py-4">
                    {{$city->user->name}}
                </td>
                <td class="px-6 py-4">
                    <div class="flex-none">
                        <a class="px-3 py-2 rounded me-3 bg-black text-white" href="{{ route('cities.edit', $city) }}">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        <a class="px-3 py-2 rounded bg-danger text-white" href="{{ route('cities.destroy', $city) }}" onclick="event.preventDefault(); if (confirm('Sei sicuro di voler eliminare questo comune?')) { document.getElementById('delete-form-{{$city->id}}').submit(); }">
                            <i class="fa-solid fa-trash"></i>
                        </a>

                        <form id="delete-form-{{$city->id}}" action="{{ route('cities.destroy', $city) }}" method="POST" style="display: none;">
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