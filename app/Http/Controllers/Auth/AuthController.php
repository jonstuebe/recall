<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/';

    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['getLogout','']]);
    }

    private function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*()_+=-";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public function redirectToFacebook()
    {
        if(!\Auth::check())
        {
            return Socialite::driver('facebook')->redirect();
        }

        return redirect('');
    }

    public function redirectToTwitter()
    {
        if(!\Auth::check())
        {
            return Socialite::driver('twitter')->redirect();
        }

        return redirect('');
    }

    public function twitterCallback()
    {
        $_user = Socialite::driver('twitter')->user();
        $user = User::find($_user->getId());
        $email = ($_user->getEmail()) ? $_user->getEmail() : '';

        if(!$user)
        {
            $pass = $this->randomPassword();
            $user = User::create([
                'id'       => $_user->getId(),
                'name'     => $_user->getName(),
                'email'    => $email,
                'password' => \Hash::make($pass)
            ]);
            $user = User::find($_user->getId());
        }

        $login = \Auth::login($user);

        if(\Auth::check())
        {
            return redirect('');
        }
    }

    public function facebookCallback()
    {
        $_user = Socialite::driver('facebook')->user();
        $user = User::find($_user->getId());
        $email = ($_user->getEmail()) ? $_user->getEmail() : '';

        if(!$user)
        {
            $pass = $this->randomPassword();
            $user = User::create([
                'id'       => $_user->getId(),
                'name'     => $_user->getName(),
                'email'    => $email,
                'password' => \Hash::make($pass)
            ]);
            $user = User::find($_user->getId());
        }

        $login = \Auth::login($user);

        if(\Auth::check())
        {
            return redirect('');
        }
    }

    public function getLogout()
    {
        $logout = \Auth::logout();
        return redirect('');
    }
}
