<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Utilities\ValidationUtilities;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcademyUpdateRequest extends FormRequest
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

        $academiesId = Academy::all()->map(fn($ac) => $ac->id);
        return [
            'academy_id' => 'required|' . Rule::in($academiesId),
            'name' => 'nullable|Letter_space|unique:academies,name|min:2',
            'abbreviation' => 'nullable|Letter_num_space|unique:academies,abbreviation|min:2',

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }

    /**
     * @param null $keys
     *
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all();
        $data['academy_id'] = $this->route('id');
        return $data;
    }

    public function messages()
    {
        return ValidationUtilities::customMessages();
    }
}
