<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Validation\Rule;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CommentUpdateRequest extends FormRequest
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
        $commentsid = Comment::all()->map(fn($c) => $c->id);
        return [
            'comment_id' => 'required|' . Rule::in($commentsid)
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
        $data['comment_id'] = $this->route('id');
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
