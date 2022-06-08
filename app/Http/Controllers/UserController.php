<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\StoreAvatarRequest;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('user.create', [

        ]);
    }

    /**
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UpdateUserRequest $request)
    {
        $user = User::create($request
                ->all([
                    'name', 'email'
                ]) + ['password' => bcrypt($request->post('password'))]);

        Auth::guard('web')->login($user);

        if($user){
            Mail::mailer('rmind')
                ->to($request->post('email'))
                ->send(new UserRegistered($user));
        }

        return redirect(url_locale('/notes'));
    }

    public function profile(User $user)
    {
        $this->authorize('view', $user);

       return view('user.cabinet', [
           'user' => Auth::user(),
       ]);
    }

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

        return redirect(url("/user/$user->id/cabinet"));

    }
}
