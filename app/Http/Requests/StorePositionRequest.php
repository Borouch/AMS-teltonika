<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Position;
use Illuminate\Validation\Rule;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StorePositionRequest extends FormRequest
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
        $posNames = Position::all()->map(fn($pos)=>$pos->name);
        $posAbvs= Position::all()->map(fn($pos)=>$pos->abbreviation);
        $acIds = Academy::all()->map(fn($ac)=>$ac->id);
        return [
            'name' => 'required|Letter_space|' . Rule::notIn($posNames),
            'abbreviation' => 'nullable|Letter_num_space|'.Rule::notIn($posAbvs),
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
