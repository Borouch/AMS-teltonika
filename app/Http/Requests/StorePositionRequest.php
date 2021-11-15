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
        $acNames = Academy::all()->map(fn($ac)=>$ac->name);
        return [
            'name' => 'required|regex:/^([a-žA-Ž ]*)$/|'.Rule::notIn($posNames),
            'abbreviation' => 'nullable|alpha_num',
            'academies.*' => 'required|'.Rule::in($acNames),
            

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}
