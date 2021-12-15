<?php

namespace App\Http\Requests;

use App\Models\Academy;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Validation\Rule;
use App\Utilities\ValidationUtilities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CandidateIndexRequest extends FormRequest
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
        $acs = Academy::all()->map(fn($ac)=>$ac->id);
        $pos = Position::all()->map(fn($p)=>$p->id);
        return [
            'group_by_academy' => 'nullable|' . Rule::in(['0','1']),
            'date_from'=>'nullable|date',
            'date_to'=>'nullable|date',
            'academy' => 'nullable|'.Rule::in($acs),
            'positions.*'=>'nullable|'.Rule::in($pos),
            'course'=>'nullable|'.Rule::in(Candidate::COURSES)
        ];
    }
    public function messages()
    {
        $msg = ValidationUtilities::customMessages();
        $msg['positions.*.in']='No position with such id exists';
        return $msg;
    }



    protected function failedValidation(Validator $validator)
    {
        ValidationUtilities::failedValidation($validator);
    }
}
