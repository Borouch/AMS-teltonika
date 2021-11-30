<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
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
            'user_id' => 'nullable|' . Rule::in($usersIds)
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
        return UserService::validationMessages();
    }

}
