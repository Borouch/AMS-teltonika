<?php

namespace App\Http\Requests;

use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StatByMonthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'month_number' => 'required|integer|between:1,12',
        ];

    }

    /**
     * @param null $keys
     * 
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all();
        $data['month_number'] = $this->route('month_number');
        return $data;
    }

    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}
