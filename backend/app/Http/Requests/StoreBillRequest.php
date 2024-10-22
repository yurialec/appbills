<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBillRequest extends FormRequest
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
            "bill_value" => "required|decimal:2",
            "due_date" => "required|date",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "O campo nome é obrigatório.",
            "name.min" => "O nome deve ter pelo menos 4 caracteres.",
            "name.max" => "O nome não pode ter mais que 255 caracteres.",
            "bill_value.required" => "O valor da conta é obrigatório.",
            "bill_value.decimal" => "O valor da conta deve ser decimal.",
            "due_date.required" => "A data de vencimento é obrigatória.",
            "due_date.date" => "A data de vencimento deve ser uma data válida.",
        ];
    }
}
