@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Crea nuovo tag
    </h2>
</div>

<div class="w-75 p-5 m-auto">

    <form action="{{ route('tags.store', $domain) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
            @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}">
            @error('description')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Tipologia</label>
            <select class="form-select form-select-lg mb-3 @error('type') is-invalid @enderror" id="type" name="type" aria-label=".form-select-lg example">
                <option class="text-body-tertiary" selected disabled>Seleziona...</option>
                @foreach($tags as $type => $tagCollection)
                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach                        
            </select>
            @error('type')
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