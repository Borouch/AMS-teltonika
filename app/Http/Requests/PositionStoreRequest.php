<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Position;
use Illuminate\Validation\Rule;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PositionStoreRequest extends FormRequest
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
        $acIds = Academy::all()->map(fn($ac)=>$ac->id);
        return [
            'name' => 'required|Letter_space|unique:positions,name|min:2',
            'abbreviation' => 'nullable|Letter_num_space|unique:positions,abbreviation|min:2',
            'academies.*' => 'required|' . Rule::in($acIds),

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
