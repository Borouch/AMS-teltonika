<?php

namespace App\Http\Requests;

use App\Models\Candidate;
use Illuminate\Validation\Rule;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CommentUpdateRequest extends FormRequest
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
        $ids = Candidate::all()->map(fn($c)=>$c->id);
        return [
            'content' => 'nullable|text|max:1000',
            'candidate_id' => 'nullable|' . Rule::in($ids)
        ];
    }
    public function messages()
    {
        return ValidationUtilities::customMessages();
    }
    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}
