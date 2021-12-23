<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Utilities\ValidationUtilities;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as SupportValidator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignPermissionRequest extends FormRequest
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
        $permissionsIds = Permission::all()->map(fn($p) => $p->id);
        $usersIds = User::all()->map(fn($u) => $u->id);
        $data = SupportValidator::make(
            $this->only('user_id'),
            [
                'user_id' => 'required|' . Rule::in($usersIds),
            ],

            ValidationUtilities::customMessages()

        )->validate();

        $user = User::find($data['user_id']);
        $userPermissionsIds = $user->permissions()->get()->map(fn($r) => $r->id);
        return [
            'permissions.*' => 'required|distinct|' . Rule::notIn($userPermissionsIds) . '|' . Rule::in($permissionsIds),
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
        $data['user_id'] = $this->route('user_id');

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
