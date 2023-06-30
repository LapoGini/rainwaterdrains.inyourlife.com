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
            <div class="modal-container d-flex justify-content-center align-items-center">
                <select class="form-select form-select-lg mb-3 @error('type') is-invalid @enderror" id="type" name="type" aria-label=".form-select-lg example">
                    <option class="text-body-tertiary" selected disabled>Seleziona...</option>
                    @foreach($tags as $type => $tagCollection)
                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach                        
                </select>
                <button type="button" id="addTagButton" class="rounded fw-bold bg-black text-light border-0 py-2 px-3 mb-3">Aggiungi tag</button>
            </div>
            @error('type')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div id="addTagModal" class="modal">
            <div class="modal-overlay"></div>
            <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
                <div class="card modal-card">
                    <div class="card-header text-end">
                        <a class="text-decoration-none text-black fw-bolder" href="{{ route('tags.create', $domain) }}">X</a>
                    </div>
                    <div class="card-body">
                    <input type="text" id="newTagInput" placeholder="Inserisci il nuovo tag" class="form-control mb-3">
                    <button type="button" id="confirmTagButton" class="rounded fw-bold bg-black text-light border-0 py-2 px-3 mb-3">Aggiungi</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-black text-light border-0 py-2 px-3">
            AGGIUNGI 
        </button>
        <a href="{{ route('tags.index', $domain) }}" class="btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-secondary text-light border-0 ms-3 py-2 px-3">
            Indietro
        </a>                             
    </form>
</div>

<script>
    // Prendo il modale
    document.getElementById("addTagButton").addEventListener("click", function() {
        document.getElementById("addTagModal").style.display = "block";
    });

    // Aggiungo il nuovo tag al modale
    document.getElementById("confirmTagButton").addEventListener("click", function() {
        var newTag = document.getElementById("newTagInput").value;

        if (newTag) {
            var select = document.getElementById("type");
            var option = document.createElement("option");
            option.value = newTag;
            option.text = newTag;
            select.add(option);

            // Seleziono il nuovo tag nella select
            option.selected = true;
        }

        document.getElementById("addTagModal").style.display = "none";
    });
</script>


@endsection