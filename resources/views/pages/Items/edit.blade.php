@extends('layouts.app')
@section('content')
<div class="w-100 p-5 m-auto">
    <div class="pb-5">
        <a href="{{ $prevItemId ? route('items.edit', $prevItemId) : '#' }}" class="prevNext btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-primary text-light border-0 py-2 px-3{{ $prevItemId ? '' : ' disabled' }}">
            Precedente
        </a>
        <a href="{{ $nextItemId ? route('items.edit', $nextItemId) : '#' }}" class="prevNext btn d-inline-flex rounded align-items-center text-decoration-none fw-bold bg-primary text-light border-0 py-2 px-3{{ $nextItemId ? '' : ' disabled' }}">
            Successivo
        </a>
    </div>

    <div class="row">
        <div class="col-6">
            <form action="{{ route('items.update', $item) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Operatore</label>
                    <input readonly type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $item->user->name) }}">
                    @error('name')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="comune" class="fw-light fst-italic d-block text-gray-700 text-sm font-bold mb-2">
                        Seleziona un comune:
                    </label>
                    <select id="comune" name="comune" class="w-100 border border-gray-300 rounded px-4 py-2">
                        <option value="">Nessuno</option>
                        @foreach($comuni as $comune)
                            <option value="{{ $comune->id }}" {{ old('comune', $item->street->city_id) == $comune->id ? 'selected' : '' }}>{{ $comune->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitudine</label>
                    <input readonly type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $item->latitude) }}">
                    @error('latitude')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitudine</label>
                    <input readonly type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $item->longitude) }}">
                    @error('longitude')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="altitude" class="form-label">Altezza</label>
                    <input readonly type="text" class="form-control @error('altitude') is-invalid @enderror" id="altitude" name="altitude" value="{{ old('altitude', $item->altitude) }}">
                    @error('altitude')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="time_stamp_pulizia" class="form-label">Data</label>
                    <input type="text" class="form-control @error('time_stamp_pulizia') is-invalid @enderror" id="time_stamp_pulizia" name="time_stamp_pulizia" value="{{ old('time_stamp_pulizia', $item->time_stamp_pulizia) }}">
                    @error('time_stamp_pulizia')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="street" class="d-block text-gray-700 text-sm font-bold mb-2">
                        Seleziona un Strada:
                    </label>
                    <select id="street" name="street" class="w-100 border border-gray-300 rounded px-4 py-2">
                        @if($streets->count() == 0)
                            <option value="">Nessuna Strada</option>
                        @else
                            <option value="">Nessuna</option>
                            @foreach($streets as $street)
                                <option value="{{ $street->id }}" {{ old('street', $item->street_id) == $street->id ? 'selected' : '' }}>{{ $street->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <input type="text" class="form-control @error('note') is-invalid @enderror" id="note" name="note" value="{{ old('note', $item->note) }}">
                    @error('note')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="civic" class="form-label">Ubicazione</label>
                    <input type="text" class="form-control @error('civic') is-invalid @enderror" id="civic" name="civic" value="{{ old('civic', $item->civic) }}">
                    @error('civic')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="height" class="form-label">Lunghezza</label>
                    <input type="text" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ round(old('height', $item->height), 2) }}">
                    @error('height')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="width" class="form-label">larghezza</label>
                    <input type="text" class="form-control @error('width') is-invalid @enderror" id="width" name="width" value="{{ round(old('width', $item->width), 2) }}">
                    @error('width')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="depth" class="form-label">Profondità</label>
                    <input type="text" class="form-control @error('depth') is-invalid @enderror" id="depth" name="depth" value="{{ round(old('depth', $item->depth), 2) }}">
                    @error('depth')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    @foreach($types as $typeId => $type)
                        <label class="d-block text-gray-700 text-sm font-bold mb-2 pt-4">
                            {{ $type }}:
                        </label>
                        <select id="tags_{{$typeId}}" name="{{strtolower($type)}}_tag_id" class="w-100 border border-gray-300 rounded px-4 py-2">
                            <option value="" {{ is_null($currentTags) || empty($currentTags->{strtolower($type) . '_tag_id'}) ? 'selected' : '' }}>Nessun tag</option>
                            @foreach($tagData[$typeId] as $tag)
                                <option value="{{ $tag->id }}" {{ !is_null($currentTags) && $currentTags->{strtolower($type) . '_tag_id'} === $tag->id ? 'selected' : '' }}>
                                    {{$tag->name}}
                                </option>
                            @endforeach
                        </select>
                    @endforeach
                </div>


                <button type="submit" class="btn btn-success d-inline-flex">
                    Modifica
                </button>
                <a onclick="window.close()" class="btn btn-secondary d-inline-flex">
                    Chiudi
                </a>
            </form>
        </div>
        <div class="col-6">
            <div class="img_caditoia mb-5">
                <img class="w-100" src="{{ $item->pic_link }}" alt="">
            </div>
            <div id="map" data-latitude="{{ $item->latitude }}" data-longitude="{{ $item->longitude }}" style="max-width: 100%; height: 300px"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('nav').hide();

        var latitude = $('#map').data('latitude');
        var longitude = $('#map').data('longitude');


        $('.prevNext').click(function(e) {
            if ($(this).hasClass('disabled')) {
                e.preventDefault();
            }
        });

        $('#comune').change(function() {
            var selectedComune = $(this).val();
            $('#street').val('');
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
        });
        function initMap(latitude, longitude) {
            var latLng = {lat: latitude, lng:  longitude};

            var map = new google.maps.Map(document.getElementById('map'), {
                center: latLng,
                zoom: 14
            });

            // Aggiungere marker per la posizione
            var marker = new google.maps.Marker({
                position: latLng,
                map: map
            });
        }
        initMap(parseFloat(latitude), parseFloat(longitude));
    });
</script>



@endsection

