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
                <a class="px-3 py-2 rounded me-3 bg-black text-white" href="{{ route('items.edit', $item) }}">
                    <i class="fas fa-pen-to-square"></i>
                </a>
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
