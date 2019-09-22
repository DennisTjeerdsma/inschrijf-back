<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
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
        return [
            'title' => 'required|min:3|max:48',
            'starttime' => 'required|date|after:today',
            'descriptions' => 'nullable',
            'enroll' => 'required',
            'enrolltime' => 'nullable|required_if:enroll,1|date|before:starttime|after:today',
            'maxparticipants' => 'nullable|required_if:enroll,1|integer'
        ];
    }
}
