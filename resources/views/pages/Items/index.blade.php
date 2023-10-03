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
                                {{ $types[$type] }}:
                            </label>
                            <div class="tag-box pb-4">
                                @foreach($tags as $tag)
                                    <label for="tag_{{$tag->id}}" class="inline-flex items-center">
                                        <input id="tag_{{$tag->id}}" name="tags[{{$type}}][]" value="{{ $tag->id }}" type="checkbox" class="border-gray-300 rounded px-4 py-2 mr-2">
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
    <div class="modal fade" id="modalErrore" tabindex="-1" aria-labelledby="modalErroreLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-danger border-3">
                <div class="modal-header">
                    <h1 class="modal-title text-danger fs-5" id="modalErroreLabel">Errore: Immagini non trovate</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Non sono state trovate immagini nel percorso specificato!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSuccess" tabindex="-1" aria-labelledby="modalErroreLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-success border-3">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalErroreLabel">Immagini trovate con successo!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Per scaricare il File Zip clicca su <span class="text-decoration-underline">Apri file</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDeleteErrore" tabindex="-1" aria-labelledby="modalErroreLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-danger border-3">
                <div class="modal-header">
                    <h1 class="modal-title text-danger fs-5" id="modalErroreLabel">Caditoie non cancellabili!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Nessuna caditoia può essere resa cancellabile!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDeleteSuccess" tabindex="-1" aria-labelledby="modalErroreLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-success border-3">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalErroreLabel">Caditoie cancellabili!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    {{ $dataTable->table() }}
</div>
{{ $dataTable->scripts() }}


<script>
    // funzione per eliminazione della caditoie
    function destroy(url) {

        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function (data) {
                
                if (data) {
                    location.reload();

                }
                else {
                    alert(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Errore AJAX:', textStatus, errorThrown);
                alert("Si è verificato un errore! Riprova!");
            }
        });
    }

    $(document).ready(function() {
        $('.select2').select2();
        $('#deletableButton').hide();
        hideDeletableButton();
        hideZipButton();

        $('#zanetti-table-download').on('xhr.dt', function (e, settings, json, xhr) {
            console.log(json);

            var ids = json.data.map(function(item) {
                return item.id;
            });

            // Invia gli ID al server utilizzando AJAX
            $.ajax({
                url: '/save-ids-to-session',
                method: 'POST',
                data: {
                    ids: ids
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        // Prendere i filtri all'invio del form
        $('form').submit(function(event) {
            event.preventDefault();
            window.LaravelDataTables["zanetti-table-download"].ajax.url( '/items?' + $(this).serialize()).load();
            if($("#client").val() !== "") {
                $(".buttons-csv, .buttons-excel").show();
                $('#deletableButton').show();
                $('#downloadZip').show();
            }
        });
        // button per resettare i filtri
        $('#resetButton').on('click', function() {
            resetFilters();
        });
        // button per eliminare caditoie cancellabili
        $('#deletableButton').on('click', function() {
            deleteSewers();
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
        // Funzione per resettare i filtri
        function resetFilters() {
            window.LaravelDataTables["zanetti-table-download"].ajax.url( '/items').load();
            $('#fromDate, #toDate').val('');
            $('#client, #comune, #street, #operator').val('').trigger('change.select2');
            $('input[type="checkbox"]').prop('checked', false);
            $('#deletableButton').hide();
            hideDownloadButtons();
            hideDeletableButton();
            hideZipButton();
        }
        function downloadFileZip(filename, data) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:application/zip;base64,' + data);
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
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
                    var risposta = JSON.parse(response);
                    var modalErrore = $('#modalErrore');
                    var modalSuccess = $('#modalSuccess');

                    if (risposta.success == false) {
                        modalErrore.modal('show');
                    } else {
                        var filename = 'immagini_' + fromDateId + '_' + toDateId + '_' + '.zip';
                        downloadFileZip(filename, risposta.data);
                        modalSuccess.modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }


        // Funzione per rendere cancellabili le caditoie
        function deleteSewers() {
            let text = "Sei sicuro di voler renedere cancellabili queste caditoie?\nScegli Ok o Annulla.";
            if (confirm(text)) {
                $.ajax({
                url: "{{ route('items.deleteSewers') }}",
                method: "GET",
                    success: function(response) {
                        console.log(response);
                        var risposta = JSON.parse(response);
                        var modalErrore = $('#modalDeleteErrore');
                        var modalSuccess = $('#modalDeleteSuccess');

                        if(risposta.success == false) {
                            modalErrore.modal('show');
                        } else {
                            modalSuccess.find('.modal-body span').text(risposta.data.cancellabile.length);
                            if (risposta.data.non_cancellabile.length === 0) {
                                modalSuccess.find('.modal-body').html('Tutte le caditoie sono cancellabili!');
                            } else {
                                var numMadeDeletable = risposta.data.cancellabile.length;
                                var numNotMadeDeletable = risposta.data.non_cancellabile.length;
                                var difference = numMadeDeletable - numNotMadeDeletable;

                                modalSuccess.find('.modal-body').html('Hai reso cancellabili ' + difference + ' caditoie, ' + risposta.data.non_cancellabile.length + ' di quelle selezionate, lo sono già!');
                            }
                            modalSuccess.modal('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                })
            }
        }
    });
</script>


@endsection
