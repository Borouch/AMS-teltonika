<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Position;
use App\Utilities\ValidationUtilities;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionUpdateRequest extends FormRequest
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
        $positionsId = Position::all()->map(fn($pos) => $pos->id);
        $academiesId = Academy::all()->map(fn($ac) => $ac->id);
        return [
            'position_id' => 'required|' . Rule::in($positionsId),
            'name' => 'nullable|Letter_space|unique:positions,name|min:2',
            'abbreviation' => 'nullable|Letter_num_space|unique:positions,abbreviation|min:2',
            'academies.*' => 'nullable|' . Rule::in($academiesId),
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['position_id'] = $this->route('id');
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
