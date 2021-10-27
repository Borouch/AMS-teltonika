<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CandidateSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|alpha',
            'surnname' => 'nullable|alpha',
            'phone' => 'nullable|regex:/^([\+][0-9]*)$/|min:9',
            'email' => 'nullable',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'error' => 'Invalid data sent',
            'details' => $errors->messages(),
        ]);
        throw new ValidationException($validator, $response);
    }
}
