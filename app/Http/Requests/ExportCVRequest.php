<?php

namespace App\Http\Requests;

use App\Models\Candidate;
use App\Utilities\ValidationUtilities;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportCVRequest extends FormRequest
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
        $candidatesId = Candidate::all()->map(fn($c) => $c->id);

        return [
            'candidate_id' => 'required|' . Rule::in($candidatesId),

        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['candidate_id'] = $this->route('id');
        return $data;
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
