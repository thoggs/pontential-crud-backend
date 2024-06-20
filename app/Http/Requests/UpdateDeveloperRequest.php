<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UpdateDeveloperRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $developerId = $this->route('developer');

        return [
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:developers,email,'.$developerId,
            'age' => 'required|integer|min:1|max:999',
            'hobby' => 'required|string|max:100',
            'birthDate' => 'required|date|date_format:Y-m-d',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    public function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'metadata' => [
                'message' => $validator->errors()->toArray(),
            ],
            'data' => [],
        ], ResponseAlias::HTTP_BAD_REQUEST);

        throw new HttpResponseException($response);
    }
}
