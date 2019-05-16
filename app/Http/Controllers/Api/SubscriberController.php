<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class SubscriberController extends ApiController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function subscribe(Request $request)
    {
        $this->validate($request,[
            'user_id'=> 'required|integer'
        ]);
        /** @var integer $leading_user_id */
        $leading_user_id = $request->get('user_id');
        User::findOrFail($leading_user_id);

        $user = \Auth::user();
        abort_if($user->following()->where('leading_id', '=', $leading_user_id)->exists(), 400, 'Already following');
        $user->following()->attach($leading_user_id);

        return $this->jsonResponse([]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function unSubscribe(Request $request)
    {
        $this->validate($request,[
            'user_id'=> 'required|integer'
        ]);

        $user = \Auth::user();
        $leading_user_id = $request->get('user_id');
        abort_if(!$user->following()->where('leading_id','=',$leading_user_id)->exists(),404,'User Not Followed');

        $user->following()->detach($leading_user_id);

        return $this->jsonResponse([]);

    }

    public function getFollowers()
    {
        $user = \Auth::user();

        return $this->jsonResponse($user->following);
    }

    public function getLeading()
    {
        $user = \Auth::user();

        return $this->jsonResponse($user->followed);
    }
}