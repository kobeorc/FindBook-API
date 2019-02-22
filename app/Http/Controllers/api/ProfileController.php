<?php
namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends ApiController
{
    public function inventory()
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->jsonResponse($user->inventory);
    }

    public function current()
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->jsonResponse($user);
    }
}
