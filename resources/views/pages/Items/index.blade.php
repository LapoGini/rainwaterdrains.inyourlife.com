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
                        <select id="client" name="client" class="select2 w-100 border border-gray-300 rounded px-4 py-2">
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
                        <select id="comune" name="comune" class="select2 w-100 border border-gray-300 rounded px-4 py-2">
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
                        <select id="street" name="street" class="select2 w-100 border border-gray-300 rounded px-4 py-2">
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
                        <select id="operator" name="operator" class="select2 w-100 border border-gray-300 rounded px-4 py-2">
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
                    <p id="confirm-delete" class="fw-light fst-italic"></p>
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

    <div class="button-zip text-end">
        <a id="downloadZip" class="btn btn-success">Scarica ZIP<i class="ps-2 fa-solid fa-file-zipper"></i></a>
    </div>

    {{ $dataTable->table() }}
</div>
{{ $dataTable->scripts() }}

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('#deletableButton').hide();
        hideDownloadButtons();
        /*$('#zanetti-table-download').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('items.filterData') }}",
                data: function(d) { 
                    d.clientId = $('#client').val();
                    d.comuneId = $('#comune').val();
                    d.streetId = $('#street').val();
                    d.fromDateId = $('#fromDate').val();
                    d.toDateId = $('#toDate').val();
                    d.operatorId = $('#operator').val();
                    d.selectedTags = [];
                    $('input[name="tags[]"]:checked').each(function() {
                        d.selectedTags.push($(this).val());
                    });
                },
            },
            //",
            initComplete: function(setting, json) {
                hideDownloadButtons();
                hideDeletableButton();
                hideZipButton();
            },
            dom: 'Bfltip',
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/it-IT.json',
            },
            ,
        });*/
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
            $('#confirm-delete').show();
            // Nascondere il paragrafo
            setTimeout(function() {
                $('#confirm-delete').hide();
            }, 5000);
        })
        // button per Zip
        $('#downloadZip').on('click', function() {
            downloadZip();
        })
        // dati select on change di clienti
        $('#client').change(function() {
            var selectedClient = $(this).val();
            $('#comune').html('');
            $('#street').html('');
            if(selectedClient !== '') {
                $.ajax({
                    url: "items/city_id/" + selectedClient, 
                    type: 'GET',
                    success: function(response) {
                        $('#comune').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log('error');
                    }
                });
            }
        });
        // dati select on change di comuni
        $('#comune').change(function() {
            var selectedComune = $(this).val();
            if ($(this).val() === '') {
                $('#street').html('');
            } else {
                $.ajax({
                    url: "/items/street/" + selectedComune, 
                    type: 'GET',
                    success: function(response) {
                        $('#street').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log('error');
                    }
                });
            }
        });

        // funzione per nascondere i buttons
        function hideDownloadButtons() {
            $('.buttons-csv, .buttons-excel').hide();
        }
        // funzione per mostrare i buttons
        function showDownloadButtons() {
            $('.buttons-csv, .buttons-excel').show();
        }
        // funzione per nascondere il button Zip
        function hideZipButton() {
            $('#downloadZip').hide();
        }
        // funzione per mostrare il button Zip
        function showZipButton() {
            $('#downloadZip').show();
        }
        // funzione per nascondere il button per le caditoie cancellabili
        function hideDeletableButton() {
            $('#deletableButton').hide();
        }
        // funzione per mostrare il button per le caditoie cancellabili
        function showDeletableButton() {
            $('#deletableButton').show();
        }

        // funzione per Zippare le immagini filtrate
        function downloadZip() {
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
            $.ajax({
                url: "{{ route('items.downloadZip') }}",
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
                    console.log(response);
                    // Avvia il download del file ZIP
                    //window.location.href = response;
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
        // funzione per applicare i filtri
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
                    var table = $('#zanetti-table-download').DataTable();
                    table.clear().rows.add(response.data).draw(); // Aggiornare la tabella con i nuovi dati filtrati
                    $('#deletableButton').show();
                    if ($('#client').val() === '') {
                        hideDownloadButtons();
                        hideDeletableButton();
                        hideZipButton();
                    } else {
                        showDownloadButtons();
                        showDeletableButton();
                        showZipButton();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
        // Funzione per resettare i filtri
        function resetFilters() {
            $('#fromDate, #toDate').val('');
            $('#client, #comune, #street, #operator').val('').trigger('change.select2');
            $('input[name="tags[]"]').prop('checked', false);
            $('#deletableButton').hide();
            hideDownloadButtons();
            hideDeletableButton();
            hideZipButton();
            applyFilters();
        }
        // Funzione per rendere cancellabili le caditoie
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
                        text = "Hai reso cancellabili le caditoie!";
                        document.getElementById("confirm-delete").innerHTML = text;
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                })
            } else {
                text = "Le Caditoie non verranno eliminate!";
            }
            document.getElementById("confirm-delete").innerHTML = text;
        }
    });
</script>


@endsection
