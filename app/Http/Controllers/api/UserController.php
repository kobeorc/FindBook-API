<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\UserAuthToken;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function login(Request $request)
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
        ]);

        /** @var User $user */
        $user = User::query()->whereEmail($request->get('email'))->first();

        if(!password_verify($request->get('password'), $user->password))
            abort(403,'Неверный логин/пароль');

        $auth_token = factory(UserAuthToken::class)->create();
        $user->auth_token()->save($auth_token);

        return $this->jsonResponse($auth_token);
    }
}
