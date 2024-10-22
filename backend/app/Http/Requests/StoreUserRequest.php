<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|min:4|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|regex:/[A-Za-z]/|regex:/[0-9]/|regex:/[@$!%*?&]/",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "O campo nome é obrigatório.",
            "name.min" => "O nome deve ter pelo menos 4 caracteres.",
            "name.max" => "O nome não pode ter mais que 255 caracteres.",
            "email.required" => "O campo email é obrigatório.",
            "email.email" => "O email deve ser um endereço de email válido.",
            "email.unique" => "O email já está em uso.",
            "password.min" => "A senha deve ter pelo menos 8 caracteres.",
            "password.letters" => "A senha deve conter letras.",
            "password.mixedCase" => "A senha deve conter letras maiúsculas e minúsculas.",
            "password.numbers" => "A senha deve conter números.",
            "password.symbols" => "A senha deve conter símbolos.",
        ];
    }
}
