<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AuthRequest
 *
 * @property string $login
 * @property string $password
 * @property string $password_confirmed
 *
 * @package App\Http\Requests
 */
class RegisterRequest extends FormRequest
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
            'login'    => 'required|max:255|unique:users',
            'password' => 'required|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'login.required'     => 'Логин не может быть пустым',
            'login.max'          => 'Максимум 255 симоволов',
            'login.unique'       => 'Пользователь с таким логином уже существует',
            'password.required'  => 'Пароль не может быть пустым',
            'password.confirmed' => 'Подтердите пароль',
        ];
    }
}
