<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:contacts,email',
            'phone' => 'required|string|max:20|unique:contacts,phone',
            'mobile' => 'required|string|max:20|unique:contacts,mobile',
            'address' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo name é obrigatório.',
            'name.string' => 'O campo name deve ser um texto.',
            'name.max' => 'O name não pode ter mais de 255 caracteres.',
            
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            
            'mobile.required' => 'O campo mobile é obrigatório.',
            'mobile.string' => 'O campo mobile deve ser um texto.',
            'mobile.max' => 'O mobile não pode ter mais de 20 caracteres.',
            'mobile.unique' => 'Este número de celular já está cadastrado.',

            'phone.required' => 'O campo phone é obrigatório.',
            'phone.string' => 'O campo phone deve ser um texto.',
            'phone.max' => 'O phone não pode ter mais de 20 caracteres.',
            'phone.unique' => 'Este número de telefone já está cadastrado.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}