<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LogInRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @param LogInRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(LogInRequest $request) {

        if (!Auth::guard('web')->attempt($request->only(['email', 'password']))) {
            return response(['errors' => [
                'unauthorized' => true,
                ],
            ], 401);
        }

        $token = Auth::user()->createToken('authToken')->accessToken;

        return response([
            'user' => new UserResource(Auth::user()),
            'access_token' => $token
        ], 200);
    }

}

