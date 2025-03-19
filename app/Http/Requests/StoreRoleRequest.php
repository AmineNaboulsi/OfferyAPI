<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|unique:roles,name",
        ];
    }
    /**
     *
     */
    public function messages()
    {
        return [
            "name.required" => "Le nom est requis",
            "name.string" => "Le nom doit être une chaîne de caractères",
            "name.exists" => "Le nom existe déjà dans la base de données",
        ];
    }
    /**
     *
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator,
            response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422));
    }
}
