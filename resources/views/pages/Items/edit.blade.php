@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Modifica 
    </h2>
</div>

<div class="w-75 p-5 m-auto">

    <form action="{{ route('items.update', $item) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Operatore</label>
            <input disabled type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $item->user->name) }}">
            @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="comune" class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                Seleziona un comune:
            </label>
            <select id="comune" name="comune" class="w-100 border border-gray-300 rounded px-4 py-2">
                <option value="">Tutti</option>
                @foreach($comuni as $comune)
                    <option value="{{ $comune->id }}" {{ old('comune', $item->comune_id) == $comune->id ? 'selected' : '' }}>{{ $comune->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Latitudine</label>
            <input disabled type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $item->latitude) }}">
            @error('latitude')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="longitude" class="form-label">Longitudine</label>
            <input disabled type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $item->longitude) }}">
            @error('longitude')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="altitude" class="form-label">Altezza</label>
            <input disabled type="text" class="form-control @error('altitude') is-invalid @enderror" id="altitude" name="altitude" value="{{ old('altitude', $item->altitude) }}">
            @error('altitude')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="time_stamp_pulizia" class="form-label">Data</label>
            <input type="text" class="form-control @error('time_stamp_pulizia') is-invalid @enderror" id="time_stamp_pulizia" name="time_stamp_pulizia" value="{{ old('name', $item->time_stamp_pulizia) }}">
            @error('time_stamp_pulizia')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="street" class="d-block text-gray-700 text-sm font-bold mb-2">
                Seleziona un Strada:
            </label>
            <select id="street" name="street" class="w-100 border border-gray-300 rounded px-4 py-2">
                <option value="">Tutti</option>
                @foreach($streets as $street)
                    <option value="{{ $street->id }}">{{ $street->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Note</label>
            <input type="text" class="form-control @error('note') is-invalid @enderror" id="note" name="note" value="{{ old('name', $item->note) }}">
            @error('note')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="civic" class="form-label">Ubicazione</label>
            <input type="text" class="form-control @error('civic') is-invalid @enderror" id="civic" name="civic" value="{{ old('name', $item->civic) }}">
            @error('civic')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="height" class="form-label">Lunghezza</label>
            <input type="text" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('name', $item->height) }}">
            @error('height')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="width" class="form-label">larghezza</label>
            <input type="text" class="form-control @error('width') is-invalid @enderror" id="width" name="width" value="{{ old('name', $item->width) }}">
            @error('width')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="depth" class="form-label">Profondità</label>
            <input type="text" class="form-control @error('depth') is-invalid @enderror" id="depth" name="depth" value="{{ old('name', $item->depth) }}">
            @error('depth')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            @foreach($groupedTagsType as $type => $tags)
                <label class="d-block text-gray-700 text-sm font-bold mb-2 pt-4">
                    {{$type}}:
                </label>
                <select id="tags" name="tags" class="w-100 border border-gray-300 rounded px-4 py-2">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            @endforeach
        </div>
        
        <button type="submit" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-black text-light border-0 py-2 px-3">
            AGGIUNGI 
        </button>
        <a href="{{ route('items.index') }}" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-secondary text-light border-0 ms-3 py-2 px-3">
            Indietro
        </a>                              
    </form>
</div>

@endsection
