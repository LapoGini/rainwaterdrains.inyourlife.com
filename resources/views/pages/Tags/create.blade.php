@extends('layouts.app')
@section('content')

<div class="bg-white">
    <h2 class="container mx-auto py-3">
        Crea nuovo tag
    </h2>
</div>

<div class="w-75 p-5 m-auto">

    <form action="{{ route('tags.store', $domain) }}" name="createTag" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $newName ? $newName : old('name') }}">
            @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione</label>
            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ $newDescription ? $newDescription : old('description') }}">
            @error('description')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="type_id" class="form-label">Tipologia</label>
            <div class="modal-container d-flex justify-content-center align-items-center">
                <select class="form-select form-select-lg mb-3 @error('type_id') is-invalid @enderror" id="type_id" name="type_id" aria-label=".form-select-lg example">
                    <option class="text-body-tertiary" selected disabled>Seleziona...</option>
                    @foreach($types as $type_id => $tagType)
                        <option value="{{ $type_id }}" {{ old('type_id') == $type_id ? 'selected' : '' }}>{{ $tagType }}</option>
                    @endforeach
                    @if(request()->has('newTag'))
                        <option value="{{ request('newTag') }}" selected>
                            {{ $newTagName }}
                        </option>
                    @endif                    
                </select>
                <button type="button" id="addTagButton" class="rounded fw-bold bg-black text-light border-0 py-2 px-3 mb-3">Aggiungi tipologia</button>
            </div>
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

<div id="addTagModal" class="modal">
    <form action="{{ route('addNewTag', $domain) }}" name="addModalTag" method="POST">

        @csrf
        <div class="modal-overlay"></div>
        <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
            <div class="card modal-card">
                <div class="card-header text-end">
                    <a class="text-decoration-none text-black fw-bolder" href="{{ route('tags.create', $domain) }}">X</a>
                </div>
                <div class="card-body">
                    <input type="hidden" id="modal_name" name="name">
                    <input type="hidden" id="modal_description" name="description">
                    <input type="text" id="tag_name" name="tag_name" placeholder="Inserisci il nuovo tag" class="form-control mb-3">
                    <button type="submit" id="confirmTagButton" class="rounded fw-bold bg-black text-light border-0 py-2 px-3 mb-3">Aggiungi</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const existingTagNames = @json($allTag->pluck('name')->toArray());

    function isTagNameExists(newTag) {
        return existingTagNames.includes(newTag.toLowerCase());
    }

    // Prendo il modale
    document.getElementById("addTagButton").addEventListener("click", function() {
        document.getElementById("addTagModal").style.display = "block";
    });

    // Aggiungo il nuovo tag al modale
    document.getElementById("confirmTagButton").addEventListener("click", function() {
        var newTag = document.getElementById("tag_name").value;
        var newName = document.getElementById("name").value;
        var newDescription = document.getElementById("description").value;

        if (isTagNameExists(newTag)) {
            e.preventDefault();
            alert("Questo tag esiste già!");
            return;
        }

        if (newTag) {
            //AGGIUNGERE ID A TYPE E RISOLVERE PER FARLO SALVARE COME NUOVA COLONNA IN 
            // TAG_TYPES, FACENDO ATTENZIONE CHE NON ENTRI IN CONFILITTO CON EL ALTRE DUE TABELLE
            var select = document.getElementById("type_id");
            var option = document.createElement("option");
            option.value = newTag;
            option.text = newTag;
            select.add(option);

            // Seleziono il nuovo tag nella select
            option.selected = true;

            document.getElementById("modal_name").value = newName;
            document.getElementById("modal_description").value = newDescription;
        }

        document.getElementById("addTagModal").style.display = "none";
    });
</script>


@endsection