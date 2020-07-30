<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendForgotEmail;
use App\User;
use Reminder;
use Mail;
use Sentinel;
use Validator;

class ForgotPasswordController extends Controller
{
    public function forgot() {
        return view('auth.forgotpassword');
    }

    public function postForgot(Request $request) {
        $user = User::whereEmail($request->email)->first();
        $sentinelUser = Sentinel::findById($user->id);
        if (!$user) {
            return response()->json(['error' => 'Email не найден']);
        } else {
            $reminder = Reminder::exists($sentinelUser) ? : Reminder::create($sentinelUser);
            // $this->sendEmail($user, $reminder->code);
            dispatch(new SendForgotEmail($user, $reminder->code));

            return response()->json(['success' => 'Письмо отправлено на вашу почту']);
        }
    }

    public function reset($email, $code) {
        $user = User::byEmail($email);
        
        if (!$user) {
            abort(404);
        } else {
            if ($reminder = Reminder::exists($user, $code)) {
                return view('auth.reset-password');
            } else {
                return redirect('/');
            }
        }
    }

    public function postReset(Request $request, $email, $code) {
        $validator = Validator::make($request->all(), [
            'password' => 'confirmed|required|min:5|max:10',
            'password_confirmation' => 'required|min:5|max:10'
        ]);

        // dd($validator->messages());
        // dd('Ok');
        if ($validator->fails()) {
            return redirect()->back()->with(['error' => $validator->messages()]);
        }
        
        $user = User::byEmail($email);
        $sentinelUser = Sentinel::findById($user->id);
        // dd($sentinelUser);
        if (!$user) {
            abort(404);
        } else {
            if ($reminder = Reminder::exists($sentinelUser, $code)) {
                Reminder::complete($user, $code, $request->password);
                return redirect('/login');
            } else {
                return redirect('/');
            }
        }
    }

    private function sendEmail($user, $code) {
        Mail::send('Emails.forgot', [
            'user' => $user,
            'code' => $code
        ], function($message) use ($user) {
            $message->to($user->email);
            $message->subject("Восстановление пароля");
        });
    }
}
