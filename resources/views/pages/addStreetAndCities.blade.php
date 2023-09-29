@extends('layouts.app')
@section('content')
<div class="mx-5 pb-5 relative bg-white">
    <h2 class="py-3">
        Aggiungi il File CSV per importare comuni e vie
    </h2>

    <div class="form-clients row">
        <div class="col-6">
            <form action="{{ route('add-cities-and-streets.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                
                <div class="m-4">
                    <label for="client" class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                        Seleziona un cliente:
                    </label>
                    <select id="client" name="client" class="select2 w-100 border border-gray-300 rounded px-4 py-2">
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="m-4">
                    <label for="csv_file" class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                        Carica un file CSV:
                    </label>
                    <input type="file" id="csv_file" name="csv_file" class="border w-100 border-gray-300 rounded px-4 py-2" accept=".csv">
                </div>

                <div class="m-4">
                    <button type="submit" class="btn btn-primary">Invia</button>
                </div>
            </form>
        </div>
        <div class="col-6 pt-4">
            <div class="m-4">
                <a href="{{ route('download-esempio-csv') }}" class="btn btn-secondary">
                    Scarica il file CSV di esempio
                </a>
            </div>
        </div>
        

        @if(session('message'))
            <div class="{{ session('type') === 'success' ? 'alert alert-success' : 'alert alert-danger' }}">
                {!! session('message') !!}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector('form').addEventListener('submit', function(event) {
            const clientValue = document.getElementById('client').value;
            
            if (clientValue == null) {
                event.preventDefault();
                alert('Devi selezionare almeno un cliente.');
            }
        });
    });
</script>

@endsection

