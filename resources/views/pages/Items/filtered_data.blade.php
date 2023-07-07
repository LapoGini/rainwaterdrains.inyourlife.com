@foreach($items as $key=>$item)
<tr className="border-b dark:bg-gray-800 dark:border-gray-700">
    <td className="px-6 py-4 comune-filtro">
        {{$item->street->name}}
    </td>
    <td className="px-6 py-4 comune-filtro">
        {{$item->street->city->name}}
    </td>
    <td className="px-6 py-4 comune-filtro">
        @if (isset($groupedTags[$item->id]) && isset($groupedTags[$item->id]['Tipo Pozzetto']))
            <small class="font-bold mr-1">Tipo Pozzetto:</small>
            @foreach ($groupedTags[$item->id]['Tipo Pozzetto'] as $tag)
                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
            @endforeach
        @endif
    </td>
    <td className="px-6 py-4 comune-filtro">
        @if (isset($groupedTags[$item->id]) && isset($groupedTags[$item->id]['Stato']))
            <small class="font-bold mr-1">Stato:</small>
            @foreach ($groupedTags[$item->id]['Stato'] as $tag)
                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
            @endforeach
        @endif
    </td>
    <td className="px-6 py-4 comune-filtro">
        @if (isset($groupedTags[$item->id]) && isset($groupedTags[$item->id]['Recapito']))
            <small class="font-bold mr-1">Recapito:</small>
            @foreach ($groupedTags[$item->id]['Recapito'] as $tag)
                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
            @endforeach
        @endif
    </td>
    <td className="px-6 py-4 comune-filtro">
        {{$item->time_stamp_pulizia}}
    </td>
    <td className="px-6 py-4 comune-filtro">
        {{$item->user->name}}
    </td>
    <td className="px-6 py-4 comune-filtro">
        {{$item->note}}
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
