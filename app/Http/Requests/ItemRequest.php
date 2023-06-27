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
            'height'=> 'required|numeric',
            'width'=> 'required|numeric',
            'depth'=> 'required|numeric',
            'note' => 'string|nullable',
            'street_id'=> 'required|numeric',
            'tagsIds' => 'array'
        ];
    }
}