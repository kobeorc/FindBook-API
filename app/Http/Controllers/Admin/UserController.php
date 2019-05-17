<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        /** @var User $users */
        $users = User::where('role', '!=', User::ROLE_GUEST)->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function edit($userId)
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $userId)
    {
        $this->validate($request, [
            'newPassword' => 'sometimes|min:6',
        ]);
        /** @var User $user */
        $user = User::findOrFail($userId);
        $user->password = Hash::make($request->get('newPassword'));
        $user->save();

        return back();
    }

}