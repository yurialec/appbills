<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordCodeRequest extends FormRequest
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
            "email" => "required|email",
            "code" => "required",
            "password" => [
                "required",
                "min:8",
                "regex:/[A-Za-z]/",
                "regex:/[0-9]/",
                "regex:/[@$!%*?&]/",
            ],
        ];
    }

    public function messages(): array
    {
        return [
            "email.required" => "O campo email é obrigatório.",
            "email.email" => "O email deve ser um endereço de email válido.",
            "email.unique" => "O email já está em uso.",

            "code.required" => "O campo código é obrigatório.",

            "password.required" => "O campo senha é obrigatório.",
            "password.min" => "A senha deve ter pelo menos 8 caracteres.",
            "password.regex" => "A senha deve conter pelo menos uma letra, um número e um símbolo especial (@$!%*?&).",
        ];
    }
}
