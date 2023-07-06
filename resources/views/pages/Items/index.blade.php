@extends('layouts.app')
@section('content')

<div class="mx-5 pb-5 relative bg-white overflow-x-auto">
    <h2 class="py-3">
        Caditoie
    </h2>

    <div class="form-filter p-4 bg-body-secondary mb-5">
        <form action="">
            @csrf

            <div class="box-form row">
                <div class="box px-3 col-12 col-md-6 col-lg-4">
                    <div class="mb-4">
                        <label for="client" class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                            Seleziona un cliente:
                        </label>
                        <select id="client" name="client" class="w-100 border border-gray-300 rounded px-4 py-2">
                            <option value="">Tutti</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="comune" class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                            Seleziona un comune:
                        </label>
                        <select id="comune" name="comune" class="w-100 border border-gray-300 rounded px-4 py-2">
                            <option value="">Tutti</option>
                            @foreach($comuni as $comune)
                                <option value="{{ $comune->id }}">{{ $comune->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="street" class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                            Seleziona una strada:
                        </label>
                        <select id="street" name="street" class="w-100 border border-gray-300 rounded px-4 py-2">
                            <option value="">Tutte</option>
                            @foreach($streets as $street)
                                <option value="{{ $street->id }}">{{ $street->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box px-3 col-12 col-md-6 col-lg-4">
                    <label for="street" class="fw-light fst-italic d-block text-center text-gray-700 text-sm font-bold mb-2">
                        Seleziona un periodo di tempo
                    </label>
                    <div class="mb-3 input-group">
                        <label for="street" class="input-group-text fw-light fst-italic text-gray-700 text-sm font-bold mb-2">
                            Da:
                        </label>
                        <input type="date" id="fromDate" name="fromDate" class="form-control mb-2">
                        <label for="street" class="input-group-text fw-light fst-italic text-gray-700 text-sm font-bold mb-2">
                            A:
                        </label>
                        <input type="date" id="toDate" name="toDate" class="form-control mb-2">
                    </div>
                    <div class="mb-4">
                        <label for="operator" class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                            Seleziona un operatore:
                        </label>
                        <select id="operator" name="operator" class="w-100 border border-gray-300 rounded px-4 py-2">
                            <option value="">Tutti</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box px-3 col-12 col-md-6 col-lg-4">
                    <div class="mb-4">
                        @foreach($groupedTagsType as $type => $tags)
                            <label class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                                {{$type}}:
                            </label>
                            <div class="tag-box pb-4">
                                @foreach($tags as $tag)
                                    <label for="tag_{{$tag->id}}" class="inline-flex items-center">
                                        <input id="tag_{{$tag->id}}" name="tags[]" value="{{ $tag->id }}" type="checkbox" class="border-gray-300 rounded px-4 py-2 mr-2">
                                        <span>{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 delete-button text-start">
                    <button id="deletableButton" type="submit" class="rounded text-decoration-none fw-bold bg-danger text-light border-0 py-2 px-3">
                        ELIMINA DATI FILTRATI DA TELEFONO
                    </button>
                    <p id="confirm-delete"></p>
                </div>
                <div class="col-6 filter-buttons text-end">
                    <button id="filterButton" type="submit" class="rounded text-decoration-none fw-bold bg-primary text-light border-0 py-2 px-3">
                        FILTRA I DATI
                    </button>
                    <button id="resetButton" type="button" class="rounded text-decoration-none fw-bold bg-secondary text-light border-0 py-2 px-3 ml-2">
                        RESET
                    </button>
                </div>
            </div>
        </form>
    </div>

    <table id="zanetti-table-download" class="table table-hover w-100 text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Indirizzo
                </th>
                <th scope="col" class="px-6 py-3">
                    Dimensioni
                </th>
                <th scope="col" class="px-6 py-3">
                    Caratteristiche
                </th>
                <th scope="col" class="px-6 py-3">
                    Operatore
                </th>
                <th scope="col" class="px-6 py-3">
                    Azioni
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $key=>$item)
                <tr className="border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$item->id}}
                    </th>
                    <td className="px-6 py-4 comune-filtro">
                        {{$item->street->name}}, {{$item->street->city->name}}
                    </td>
                    <td className="px-6 py-4">
                        {{round($item->height)}}L x {{round($item->width)}}S x {{round($item->depth)}}P
                    </td>
                    <td class="px-6">
                        @if (isset($groupedTags[$item->id]))
                            @foreach ($groupedTags[$item->id] as $type => $tags)
                                <p>
                                    <small class="font-bold mr-1">{{ $type }}:</small>
                                    @foreach ($tags as $tag)
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                                    @endforeach
                                </p>
                            @endforeach
                        @endif
                    </td>
                    <td className="px-6 py-4">
                        {{$item->user->name}}
                    </td>
                    <td className="px-6 py-4">
                        <div className="flex-none">
                            <a class="px-3 py-2 rounded me-3 bg-black text-white" href="{{ route('items.edit', $item) }}"><i class="fas fa-pen-to-square"></i></a>
                            <a class="px-3 py-2 rounded bg-danger text-white" href="{{ route('items.destroy', $item) }}" onclick="event.preventDefault(); if (confirm('Sei sicuro di voler eliminare questo comune?')) { document.getElementById('delete-form').submit(); }">
                                <i class="fa-solid fa-trash"></i>
                            </a>

                            <form id="delete-form" action="{{ route('items.destroy', $item) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    
</div>

<script>
    $(document).ready(function() {
        $('#deletableButton').hide();
        hideDownloadButtons();
        $('#zanetti-table-download').DataTable({
            initComplete: function(setting, json) {
                hideDownloadButtons();
            },
            dom: 'Bfltip',
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/it-IT.json',
            },
            buttons: [
                {
                extend: 'csv',
                text: 'DOWNLOAD CSV'
                },
                {
                extend: 'excel',
                text: 'DOWNLOAD XLSX'
                }
            ],
        });
        // Prendere i filtri all'invio del form
        $('form').submit(function(event) {
            event.preventDefault();
            applyFilters();
        });
        // button per resettare i filtri
        $('#resetButton').on('click', function() {
            resetFilters();
        });
        // button per eliminare caditoie cancellabili
        $('#deletableButton').on('click', function() {
            deleteSewers();
        })


        // funzione per nascondere i buttons
        function hideDownloadButtons() {
            $('.buttons-csv, .buttons-excel').hide();
        }
        // funzione per mostrare i buttons
        function showDownloadButtons() {
            $('.buttons-csv, .buttons-excel').show();
        }
        // funzione per i filtri
        function applyFilters() {
            var clientId = $('#client').val();
            var comuneId = $('#comune').val();
            var streetId = $('#street').val();
            var fromDateId = $('#fromDate').val();
            var toDateId = $('#toDate').val();
            var operatorId = $('#operator').val();
            var selectedTags = [];
            $('input[name="tags[]"]:checked').each(function() {
                selectedTags.push($(this).val());
            });
            // chiamata AJAX per avere i dati filtrati
            $.ajax({
                url: "{{ route('items.filterData') }}",
                method: "GET",
                data: {
                    clientId: clientId,
                    comuneId: comuneId,
                    streetId: streetId,
                    fromDateId: fromDateId,
                    toDateId: toDateId,
                    operatorId: operatorId,
                    tags: selectedTags,
                },
                success: function(response) {
                    $('#zanetti-table-download tbody').html(response);
                    $('#deletableButton').show();
                    if ($('#client').val() === '') {
                        hideDownloadButtons();
                    } else {
                        showDownloadButtons();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
        // Funzione per reimpostare i filtri
        function resetFilters() {
            $('#client, #comune, #street, #operator,#fromDate, #toDate').val('');
            $('input[name="tags[]"]').prop('checked', false);
            $('#deletableButton').hide();
            hideDownloadButtons();
            applyFilters();
        }
        // Funzione per eliminazione
        function deleteSewers() {
            var clientId = $('#client').val();
            var comuneId = $('#comune').val();
            var streetId = $('#street').val();
            var fromDateId = $('#fromDate').val();
            var toDateId = $('#toDate').val();
            var operatorId = $('#operator').val();
            var selectedTags = [];
            $('input[name="tags[]"]:checked').each(function() {
                selectedTags.push($(this).val());
            });

            let text = "Sei sicuro di voler eliminare le caditoie?\nScegli Ok o Annulla.";
            if (confirm(text)) {
                $.ajax({
                url: "{{ route('items.filterData') }}",
                method: "GET",
                data: { 
                    clientId: clientId,
                    comuneId: comuneId,
                    streetId: streetId,
                    fromDateId: fromDateId,
                    toDateId: toDateId,
                    operatorId: operatorId,
                    tags: selectedTags,
                    itemCancellabile: true,
                  },
                    success: function(response) {
                        text = "Le Caditoie sono state eliminate con successo!";
                        document.getElementById("confirm-delete").innerHTML = text;
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                })
            } else {
                text = "Le Caditoie non sono state eliminate!";
            }
            document.getElementById("confirm-delete").innerHTML = text;
        }
    });
</script>


@endsection
