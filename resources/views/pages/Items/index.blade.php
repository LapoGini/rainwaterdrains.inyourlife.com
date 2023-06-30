@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Caditoie
    </h2>
</div>

<div class="container my-5 mx-auto relative p-5 bg-white overflow-x-auto">
    <table id="zanetti-table-download" class="table table-hover w-100 text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Indirizzo
                </th>
                <th scope="col" class="px-6 py-3">
                    Dimensioni
                </th>
                <th scope="col" class="px-6 py-3">
                    Caratteristiche
                </th>
                <th scope="col" class="px-6 py-3">
                    Operatore
                </th>
                <th scope="col" class="px-6 py-3">
                    Azioni
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $key=>$item)
            <tr className="border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$item->id}}
                </th>
                <td className="px-6 py-4">
                    {{$item->street->name}}, {{$item->street->city->name}}
                </td>
                <td className="px-6 py-4">
                    {{round($item->height)}}L x {{round($item->width)}}S x {{round($item->depth)}}P
                </td>
                <td class="px-6 py-4">
                    @if (isset($groupedTags[$item->id]))
                        @foreach ($groupedTags[$item->id] as $type => $tags)
                            <p>
                                <small class="font-bold mr-1">{{ $type }}:</small>
                                @foreach ($tags as $tag)
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                                @endforeach
                            </p>
                        @endforeach
                    @endif
                </td>
                <td className="px-6 py-4">
                    {{$item->user->name}}
                </td>
                <td className="px-6 py-4">
                    <div className="flex-none">
                        
                    </div>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
    
</div>


@endsection