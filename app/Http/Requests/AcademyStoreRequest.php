<?php

namespace App\Http\Requests;

use App\Models\Academy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcademyStoreRequest extends FormRequest
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
        $academiesNames = Academy::all()->map(fn($academy)=>$academy->name)->toArray();
        $academiesAbv = Academy::all()->map(fn($academy)=>$academy->abbreviation)->toArray();
        return [
            'name'=>'required|regex:/^[ a-žA-Ž]*$/|'.Rule::notIn($academiesNames),
            'abbreviation'=>'nullable|alpha_num|'.Rule::notIn($academiesAbv)
        ];
    }

    public function messages()
    {
        return ['name.not_in'=>'Name must be unique',
        'abbreviation.not_in'=>'Abbreviation must be unique'];
    }
}
