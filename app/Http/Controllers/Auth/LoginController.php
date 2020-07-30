<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sentinel;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request) {
        try {
            $credentials = [
                'login' => $request->email,
                'password' => $request->password,
            ];
            
            $user = Sentinel::authenticate($credentials);

            if (!$user) {
                return response()->json(['error' => 'Пользователь не существует']);
            };
            $slug = $user->roles()->first()->slug;
            
            $id = $user->id;

            $rememberMe = false;
            if(isset($request->remember_me)) {
                $rememberMe = true;
                Sentinel::loginAndRemember($user);
            }
            
            Sentinel::login($user);
            switch ($slug) {
                case 'admin':
                    return response()->json(['success' => 'Ok', 'slug' => 'admin']);
                    break;
                case 'user':
                    return response()->json(['success' => 'Ok', 'slug' => 'user']);
                    break;
            }
        } catch(\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
            return response()->json(['error' => 'Повторите вход через '.$e->getDelay().' секунд']);
        } catch(\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
            return response()->json(['error' => 'Ваш аккаунт еще не активирован']);
        }
    }

    public function postLogout() {
        Sentinel::logout();
        return redirect('/login');
    }
}
