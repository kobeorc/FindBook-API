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
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        /** @var User $user */
        $user = User::query()->whereEmail($request->get('email'))->first();

        if(!password_verify($request->get('password'), $user->password))
            abort(403,'Неверный логин/пароль');

        /** @var UserAuthToken $auth_token */
        $auth_token = factory(UserAuthToken::class)->make();
        $user->auth_token()->save($auth_token);

        return $this->jsonResponse($auth_token);
    }

    public function register(Request $request)
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = new User();
        $user->name = $request->get('name','');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->role = User::ROLE_USER;
        $user->status = User::STATUS_REGULAR;
        $user->setRememberToken(bcrypt(str_random(10)));
        $user->save();

        return response()->make('',201);
    }
}
