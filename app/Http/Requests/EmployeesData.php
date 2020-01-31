<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeesData extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
              'f_name'        => 'required|string|max:191',
            'l_name'        => 'required|string|max:191',
            'm_name'        => 'required|string|max:191',
            'dob'           => 'required|date_format:mm/dd/YYYY|before:today',
            'gender'        => 'required|not_in:0',
            'nationality'   => 'required|string|max:50',
            'license'       => 'required|in:yes,No',
            'phone_no'      => 'required|digits:15',
            'email'         => 'required|string|max:50|unique:employees',
            'address'       => 'required|string|max:191',
        ];
    }
}
