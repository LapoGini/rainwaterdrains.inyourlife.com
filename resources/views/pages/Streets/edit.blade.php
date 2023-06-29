@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Modifica {{$street->name}}
    </h2>
</div>

<div class="w-75 p-5 m-auto">

    <form action="{{ route('streets.update', $street) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $street->name) }}">
            @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Comune</label>
            <select class="form-select form-select-lg mb-3 @error('role') is-invalid @enderror" id="role" name="city_id" aria-label=".form-select-lg example">
                <option selected disabled>Seleziona...</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ (old('city_id', $street->city_id) == $city->id) ? 'selected' : '' }}>{{ $city->name }}</option>
                @endforeach                        
            </select>
            @error('role')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-black text-light border-0 py-2 px-3">
            AGGIUNGI 
        </button>
        <a href="{{ route('streets.index') }}" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-secondary text-light border-0 ms-3 py-2 px-3">
            Indietro
        </a>                            
    </form>
</div>


@endsection