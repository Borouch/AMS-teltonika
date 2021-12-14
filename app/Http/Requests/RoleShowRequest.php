<?php

namespace App\Http\Requests;

use App\Services\RoleService;
use App\Utilities\ValidationUtilities;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleShowRequest extends FormRequest
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
        $rolesId = Role::all()->map(fn($r) => $r->id);
        return [
            'role_id' => 'required|' . Rule::in($rolesId),
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
        $data['role_id'] = $this->route('id');
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
