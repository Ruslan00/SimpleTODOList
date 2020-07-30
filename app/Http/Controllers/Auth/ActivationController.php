<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Activation;
use App\User;
use Sentinel;

class ActivationController extends Controller
{
    public function activation($email, $code) {
        $user = User::whereEmail($email)->first();
        // dd($user);
        if (Activation::complete($user, $code)) {
            return redirect('/login');
        } else {
            return redirect('/');
        }
    }
}
