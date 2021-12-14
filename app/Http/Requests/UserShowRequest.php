<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Services\UserService;
use App\Utilities\ValidationUtilities;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserShowRequest extends FormRequest
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

        $usersIds = User::all()->map(fn ($u) => $u->id);
        return [
            'user_id' => 'required|' . Rule::in($usersIds)
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
        $data['user_id'] = $this->route('id');

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
