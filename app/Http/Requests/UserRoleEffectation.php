<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
class UserRoleEffectation extends FormRequest
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
            "role_id" => "required|integer|exists:roles,id"
        ];
    }
    public function messages() : array{
        return [
            "role_id.required" => "Le role est required",
            "role_id.integer" => "Le role doit être un entier",
            "role_id.exists" => "Le role n'existe pas dans la base de données",
        ];
    }
    public function failedValidation(Validator $validator){
        throw new ValidationException($validator ,
            response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422));
    }

}
