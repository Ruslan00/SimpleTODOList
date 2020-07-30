<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendActivationEmail;
use Validator;
use Sentinel;
use Activation;
use App\User;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function postReg(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha|min:2',
            'last_name' => 'required|alpha|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'confirmed|required|min: 5',
            'password_confirmation' => 'required|min:5|max:10'
        ],
        [
            'first_name.required' => 'Это поле обязательно для заполнения',
            'first_name.unique' => 'Пользователи с таким именем уже зарегестрирован',
            'first_name.alpha' => 'Только символы',
            'first_name.min' => 'Должно быть минимум 2 символа',
            'last_name.required' => 'Это поле обязательно для заполнения',
            'last_name.alpha' => 'Только латские буквы',
            'last_name.unique' => 'Пользователи с такой фамилией уже зарегестрирован',
            'last_name.min' => 'Должно быть минимум 2 символа',
            'email.required' => 'Это поле обязательно для заполнения',
            'email.unique' => 'Пользователи с таким email уже зарегестрирован',
            'password.required' => 'Это поле обязательно для заполнения',
            'password.min' => 'Должно быть минимум 5 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'password_confirmation.required' => 'Это поле обязательно для заполнения',
            'password_confirmation.min' => 'Должно быть минимум 5 символов',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $credentials = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
        ];
        $user = Sentinel::register($credentials);
        $activation = Activation::create($user);
        $role = Sentinel::findRoleById(2);
        $role->users()->attach($user);

        if ($user->save()) {
            // $this->sendEmail($user, $activation->code);
            dispatch(new SendActivationEmail($user, $activation->code));
            return response()->json(['success' => 'Ваша учетная запись успешно создана. Ссылка для активации отправлена на вашу почту']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }
}
