<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TagType;

class ItemRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $validatedItems = [
            'name' => 'required|string',
            'comune' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'required|numeric',
            'time_stamp_pulizia' => 'required|date',
            'street' => 'required|string',
            'note' => 'nullable|string',
            'civic' => 'required|numeric',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'depth' => 'nullable|numeric',
        ];

        $types = TagType::pluck('name', 'id');

        $validatedTagTypes = [];

        foreach ($types as $typeName) {
            $columnName = strtolower($typeName) . '_tag_id';
            $validatedTagTypes[$columnName] = 'nullable';
        }

        $result = array_merge($validatedItems, $validatedTagTypes);

        return $result;
    }
}
