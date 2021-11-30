<?php

namespace App\Http\Requests;

use App\Services\RoleService;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RoleStoreRequest extends FormRequest
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
        $names = Role::all()->map(fn($r) => $r->name);
        return [
            'name'=>'required|Letter_space|'.Rule::notIn($names),
            'guard_name'=>'nullable|Letter_space'
        ];
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
