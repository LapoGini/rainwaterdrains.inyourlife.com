@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Modifica {{$user->name}}
    </h2>
</div>

<div class="w-75 p-5 m-auto">

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
            @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
            @error('email')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="">
            @error('password')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Ruolo</label>
            <select class="form-select form-select-lg mb-3 @error('rolesIds') is-invalid @enderror" id="role" name="rolesIds[]" aria-label=".form-select-lg example">
                <option selected disabled>Seleziona...</option>
                <option value="1" {{ in_array(1, old('rolesIds', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>Admin</option>
                <option value="2" {{ in_array(2, old('rolesIds', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>Operatore</option>
                <option value="3" {{ in_array(3, old('rolesIds', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>Cliente</option>
            </select>
            @error('rolesIds')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-black text-light border-0 py-2 px-3">
            AGGIUNGI 
        </button>
        <a href="{{ route('users.index') }}" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-secondary text-light border-0 ms-3 py-2 px-3">
            Indietro
        </a>                           
    </form>
</div>


@endsection