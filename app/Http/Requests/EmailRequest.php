<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Sanctum\PersonalAccessToken;

class EmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        //Middleware can be applied to this class for additional functionality.
        $token = PersonalAccessToken::findToken($this->get('api_token'));
        if (!$token) {
            return false;
        }

        $tokenUser = $token->tokenable;

        return $tokenUser->id == $this->user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'emails' => 'required|array',
            'emails.*.email' => 'required|email',
            'emails.*.subject' => 'required|string',
            'emails.*.body' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
