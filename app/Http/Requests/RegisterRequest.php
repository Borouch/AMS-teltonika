<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $userEmails = User::all()->map(fn ($u) => $u->email);
        return [
            'email' => 'required|email|' . Rule::notIn($userEmails),
        ];
    }

    public function messages()
    {
        return [ 'email.not_in'=>"A user already exists with this email address" ];
    }
}
