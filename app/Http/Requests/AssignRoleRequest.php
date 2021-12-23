<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as SupportValidator;

class AssignRoleRequest extends FormRequest
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
        $rolesIds = Role::all()->map(fn($r) => $r->id);
        $usersIds = User::all()->map(fn($u) => $u->id);
        $data = SupportValidator::make(
            $this->only('user_id'),
            [
                'user_id' => 'required|' . Rule::in($usersIds),
            ],

            ValidationUtilities::customMessages()

        )->validate();
        $user = User::find($data['user_id']);
        $userRolesIds = $user->roles()->get()->map(fn($r) => $r->id);
        return [
            'roles.*' => 'required|distinct|' . Rule::notIn($userRolesIds) . '|' . Rule::in($rolesIds),
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
