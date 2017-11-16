<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    /**
     * Регистрация пользователся
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        tap(new User($request->all()), function ($user) use ($request) {
            $user->password = bcrypt($request->password);
        })->save();

        return response()->json([
            'registered' => true
        ]);
    }

    /**
     * Аутентификация
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('login', $request->login)->first();

        if ($user && \Hash::check($request->password, $user->password)) {
            $user->api_token = str_random(60);
            $user->save();

            return response()->json([
                'authenticated' => true,
                'api_token'     => $user->api_token,
                'user_id'       => $user->id
            ]);
        }

        return response()->json([
            'errors' => ['login' => ['Ошибка аутентификации']]
        ], 422);
    }

    /**
     * Выход
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        tap($request->user(), function ($user) {
            $user->api_token = null;
        })->save();

        return response()->json([
            'logged_out' => true
        ]);
    }
}
