<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name' => 'required|string',
            'comune' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'required|numeric',
            'time_stamp_pulizia' => 'required|date',
            'street' => 'required|string',
            'note' => 'nullable|string',
            'civic' => 'required|numeric',
            'height' => 'required|numeric',
            'width' => 'required|numeric',
            'depth' => 'required|numeric',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
