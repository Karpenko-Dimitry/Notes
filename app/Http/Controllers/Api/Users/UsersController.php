<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\StoreAvatarRequest;
use App\Http\Resources\UserResource;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * @param UpdateUserRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(UpdateUserRequest $request)
    {
        $user = User::create(array_merge($request->only(['name','email']),
            ['password' => bcrypt($request->post('password'))]));

        Mail::mailer('rmind')
            ->to($request->post('email'))
            ->send(new UserRegistered($user));

        $token = $user->createToken('authToken')->accessToken;

        return response([
            'user' => new UserResource($user),
            'access_token' => $token
        ], 200);
    }

    /**
     * @param UpdateUserRequest $request
     * @return UserResource
     */
    public function update(UpdateUserRequest $request)
    {
        /** @var User $user */
        $user = auth('api')->user();
        $user->update(array_merge($request->only(['name','email', 'avatar'])));

//        Mail::mailer('rmind')
//            ->to($request->post('email'))
//            ->send(new UserRegistered($user));

        return new UserResource($user);
    }

    /**
     * @return UserResource
     */
    public function profile(): UserResource
    {
        return new UserResource(Auth::user());
    }

    /**
     * @param StoreAvatarRequest $request
     * @param User $user
     * @return UserResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function storeAvatar(StoreAvatarRequest $request, User $user)
    {
        $this->authorize('view', $user);

        if (is_uploaded_file($request->file('user_file'))) {

            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            $path = $request->file('user_file')->storePublicly('public/img');
            $user->update(['avatar' => $path]);
        }

        return new UserResource($user);

    }
}
