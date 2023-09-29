@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Modifica {{$tag->name}}
    </h2>
</div>

<div class="w-75 p-5 m-auto">

    <form action="{{ route('tags.update', ['domain' => $domain, 'tag' => $tag]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tag->name) }}">
            @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $tag->description) }}">
            @error('description')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="type_id" class="form-label">Tipologia</label>
            <select class="form-select form-select-lg mb-3 @error('type_id') is-invalid @enderror" id="type_id" name="type_id" aria-label=".form-select-lg example">
                <option class="text-body-tertiary" selected disabled>Seleziona...</option>
                @foreach($types as $type_id => $tagType)
                    <option value="{{ $type_id }}" {{ (old('type_id') == $type_id) ? 'selected' : (($tag->type_id === $type_id) ? 'selected' : '') }}>{{ $tagType }}</option>
                @endforeach             
            </select>
            @error('type_id')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-black text-light border-0 py-2 px-3">
            AGGIUNGI 
        </button>
        <a href="{{ route('tags.index', $domain) }}" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-secondary text-light border-0 ms-3 py-2 px-3">
            Indietro
        </a>                            
    </form>
</div>


@endsection