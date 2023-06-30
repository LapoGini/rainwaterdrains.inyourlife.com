@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Modifica {{$item->name}}
    </h2>
</div>

<div className="bg-white mb-5">
    <div className="text-gray-900 grid grid-cols-1 md:grid-cols-2 gap-4  items-start">
        <div>
            <p>
                <small className="font-bold mr-1">ID:</small>
                <span>{{$item->id}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Indirizzo:</small>
                <span>{{$item->street->name}}, {{$item->street->city->name}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Altitudine:</small>
                <span>{{$item->altitude}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Latitudine:</small>
                <span>{{$item->latitude}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Longitudine:</small>
                <span>{{$item->longitude}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Accuratezza:</small>
                <span>{{$item->accuracy}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Dimensioni:</small>
                <span>{{round($item->height)}}L x {{round($item->width)}}S x {{round($item->depth)}}P</span>
            </p>
            <p>
                <small className="font-bold mr-1">Note:</small>
                <span>{{$item->note}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Operatore:</small>
                <span>{{$item->user->name}}</span>
            </p>
            <p>
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
            </p>
            <p>
                <small className="font-bold mr-1">Lunghezza:</small>
                <span>{{$item->height}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Larghezza:</small>
                <span>{{$item->width}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Profondità:</small>
                <span>{{$item->depth}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Note:</small>
                <span>{{$item->note}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Strada:</small>
                <span>{{$item->street->name}}</span>
            </p>
            <p>
                <small className="font-bold mr-1">Comune:</small>
                <span>{{$item->street->city->name}}</span>
            </p>
        </div>
    </div>
</div>

@endsection