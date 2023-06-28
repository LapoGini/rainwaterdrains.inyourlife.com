@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Modifica {{$city->name}}
    </h2>
</div>

<div class="w-75 p-5 m-auto">

    <form action="{{ route('cities.update', $city) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $city->name) }}">
            @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Ruolo</label>
            <select class="form-select form-select-lg mb-3 @error('district') is-invalid @enderror" id="role" name="district" aria-label=".form-select-lg example">
                <option value="">Seleziona...</option>
                @foreach($districts as $key => $value)
                    <option value="{{ $key }}" {{ (is_array(old('district')) && in_array($key, old('district'))) || (old('district') === null && $key == $city->district) ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach                     
            </select>
            @error('district')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="client" class="form-label">Cliente</label>
            <select class="form-select form-select-lg mb-3 @error('clients') is-invalid @enderror" id="client" name="client" aria-label=".form-select-lg example">
                <option value="">Seleziona...</option>
                @foreach($users as $user)
                    <option value="{{ $user }}" {{ old('client') && old('client')[0] == $user ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            @error('clients')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="pics" class="form-label">Foto</label>
            <div class="form-check">
                <label class="form-check-label" for="pics">Includi foto</label>
                <input class="form-check-input" type="checkbox" id="pics" name="pics" {{ old('pics') === 'Sì' || $city->pics === 'Sì' ? 'checked' : '' }}>
            </div>
        </div>

        <button type="submit" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-black text-light border-0 py-2 px-3">
            AGGIUNGI 
        </button>
        <a href="{{ route('cities.index') }}" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-secondary text-light border-0 ms-3 py-2 px-3">
            Indietro
        </a>                              
    </form>
</div>


@endsection