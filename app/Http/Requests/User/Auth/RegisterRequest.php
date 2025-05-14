<?php

namespace App\Http\Requests\User\Auth;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name'=>'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth'=>'required|date',
            'fin_code'=>'required|string|size:7|unique:user_details,fin_code',
            'email'=>'required|string|email|max:255|unique:user_details,email',
            'phone_number'=>'required|string|max:19|unique:user_details,phone_number',
            'address'=>'required|string|max:255',
            'gender'=>'required|string|max:255',
            'password'=>'required|string|min:8',
            'confirm_password'=>'required|string|min:8|same:password',
        ];
    }
}
