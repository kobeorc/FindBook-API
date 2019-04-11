<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserAuthToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends ApiController
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|max:255',
        ]);

        /** @var User $user */
        $user = User::query()->whereEmail($request->get('email'))->first();

        if (!Hash::check($request->get('password'), $user->password)) {
            abort(403, 'Неверный логин/пароль');
        }

        /** @var UserAuthToken $auth_token */
        $auth_token = factory(UserAuthToken::class)->make();
        $user->auth_token()->save($auth_token);

        return $this->jsonResponse($auth_token);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->role = User::ROLE_USER;
        $user->status = User::STATUS_REGULAR;
        $user->setRememberToken(bcrypt(Str::random()));
        $user->save();

        return response()->make('', 201);
    }

    public function registerGuest(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string|min:64|max:64'
        ]);
        abort_unless($this->checkSilentRegisterToken($request->get('token')), 403, 'Неверный токен');

        $user = new User();
        $user->name = 'guest';
        $user->role = User::ROLE_GUEST;
        $user->status = User::STATUS_REGULAR;
        $user->password = Hash::make(Str::random());
        $user->setRememberToken(Hash::make(Str::random()));
        $user->save();

        /** @var UserAuthToken $auth_token */
        $auth_token = factory(UserAuthToken::class)->make();
        $user->auth_token()->save($auth_token);

        return $this->jsonResponse($auth_token);

    }

    /**
     * @param $token
     * @return bool
     */
    public function checkSilentRegisterToken(string $token): bool
    {
        return (bool)(hash('sha256', substr_replace(substr(time(), 0, -1), config('app.s'), 4, 0)) == $token);
    }

    public function ping()
    {
        return 'pong';
    }
}
