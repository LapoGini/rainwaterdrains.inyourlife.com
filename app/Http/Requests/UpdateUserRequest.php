<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => [
                'nullable',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            ],
            'rolesIds' => 'array'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Il nome è obbligatorio.',
            'name.string' => 'Il nome deve essere una stringa.',
            'name.max' => 'Il nome non può essere più lungo di 100 caratteri.',
            
            'email.required' => 'L\'email è obbligatoria.',
            'email.email' => 'Devi inserire un\'email valida.',
            
            'password.min' => 'La password deve avere almeno 6 caratteri.',
            'password.regex' => 'La password deve contenere almeno una lettera, un numero e un carattere speciale (! $ # %).',
            
            'rolesIds.array' => 'Roles IDs deve essere un array.',
        ];
    }

}