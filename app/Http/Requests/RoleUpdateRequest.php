<?php

namespace App\Http\Requests;

use App\Services\RoleService;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RoleUpdateRequest extends FormRequest
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
        $rolesIds = Role::all()->map(fn ($r) => $r->id);
        return [
            'role_id' => 'required|' . Rule::in($rolesIds),
            'name' => 'nullable|Letter_space',
            'guard_name' => 'nullable|Letter_space',
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
        $data['role_id'] = $this->route('role_id');
        return $data;
    }

    public function messages()
    {
        return RoleService::validationMessages();
    }
    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}
