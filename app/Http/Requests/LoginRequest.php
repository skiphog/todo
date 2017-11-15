<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequest
 *
 * @property string $login
 * @property string $password
 *
 * @package App\Http\Requests
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'login'    => 'required',
            'password' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'login.required'    => 'Введите логин',
            'password.required' => 'Введите пароль',
        ];
    }
}
