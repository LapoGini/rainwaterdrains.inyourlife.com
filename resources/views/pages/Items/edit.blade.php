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
            <label for="operator" class="form-label">Operatore</label>
            <input disabled type="text" class="form-control @error('operator') is-invalid @enderror" id="operator" name="operator" value="{{ old('operator', $item->user->name) }}">
            @error('operator')
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
            <label for="lat" class="form-label">Latitudine</label>
            <input disabled type="text" class="form-control @error('lat') is-invalid @enderror" id="lat" name="lat" value="{{ old('lat', $item->latitude) }}">
            @error('lat')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="long" class="form-label">Longitudine</label>
            <input disabled type="text" class="form-control @error('long') is-invalid @enderror" id="long" name="long" value="{{ old('long', $item->longitude) }}">
            @error('long')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="alt" class="form-label">Altezza</label>
            <input disabled type="text" class="form-control @error('alt') is-invalid @enderror" id="alt" name="alt" value="{{ old('alt', $item->altitude) }}">
            @error('alt')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Data</label>
            <input type="text" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('name', $item->time_stamp_pulizia) }}">
            @error('date')
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
