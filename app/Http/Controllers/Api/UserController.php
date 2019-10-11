<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserRegisterGuestRequest;
use App\Models\User;
use App\Models\UserAuthToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends ApiController
{
    public function login(UserLoginRequest $request)
    {
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

    public function register(UserRegisterRequest $request)
    {
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

    public function registerGuest(UserRegisterGuestRequest $request)
    {
        abort_unless($this->checkSilentRegisterToken($request->get('token')), 403, 'Неверный токен');// TODO move to validate

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

    public function getUserInfo(Request $request, int $user_id)
    {
        $user = User::findOrFail($user_id);
        return $this->jsonResponse($user);
    }

    /**
     * @param $token
     * @return bool
     */
    public function checkSilentRegisterToken(string $token): bool
    {
        return $token === config('app.s');
    }

    public function ping()
    {
        return 'pong';
    }

    public function getToken()
    {
        return $this->jsonResponse(['key' => config('app.s')]);
    }
}
