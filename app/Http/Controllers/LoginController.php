<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LogInRequest;
use App\Mail\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Laravel\Socialite\Two\GoogleProvider;
use Mail;
use Session;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        if (Auth::check()){
            Auth::logout();
        }

        return redirect(url_locale('/notes'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('user.sign-in', [

        ]);
    }

    /**
     * @param LogInRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LogInRequest $request)
    {

        if (Auth::attempt($request->all(['email', 'password'])))
        {
            return redirect(url_locale('notes'));
        }


        Session::flash('message', 'Incorrect email or password');

        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider(Request $request)
    {
        /** @var GoogleProvider $provider */
        $provider = Socialite::driver('google');

        return $provider->with($request->only('state'))->redirect();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function handleProviderCallback(Request $request)
    {
        /** @var GoogleProvider $provider */
        $provider = Socialite::driver('google');
        $result = $provider->stateless()->user();
        $user = User::where('email', $result->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $result->name,
                'email' => $result->email,
                'password' => bcrypt(random_int(10, 10000))
            ]);

            Auth::guard('web')->login($user);

            if($user) {
                Mail::mailer('rmind')
                    ->to($result->email)
                    ->send(new UserRegistered($user));
            }

        } else {
            Auth::guard('web')->login($user);
        }

        if ($request->get('state') === 'web') {
            return redirect(url_locale('/notes'));
        }

        $token = Auth::user()->createToken('authToken')->accessToken;
        return redirect('http://localhost:3000/oauth-callback?token=' . $token);
    }

}
