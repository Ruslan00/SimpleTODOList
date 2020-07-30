<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Sentinel;
use Validator;
use DB;

class UserController extends Controller
{
    public function index() {
        $users = DB::select('select `u`.`id`, `u`.`first_name`, `u`.`last_name`, `u`.`email`,
            `r`.`name`
            from `users` `u`
            left join `role_users` `ru` on `u`.`id` = `ru`.`user_id`
            left join `roles` `r` on `r`.`id` = `ru`.`role_id`');
        return view('admin.user', [
            'users' => $users
        ]);
    }

    public function add() {
        $roles = DB::select('select `id`, `name` from `roles`');

        return view('admin.user_add', [
            'roles' => $roles
        ]);
    }

    public function postAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha|min:2',
            'last_name' => 'required|alpha|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min: 5',
        ],
        [
            'first_name.required' => 'Это поле обязательно для заполнения',
            'first_name.unique' => 'Пользователи с таким именем уже зарегестрирован',
            'first_name.alpha' => 'Только символы',
            'first_name.min' => 'Должно быть минимум 2 символа',
            'last_name.required' => 'Это поле обязательно для заполнения',
            'last_name.alpha' => 'Только символы',
            'last_name.unique' => 'Пользователи с такой фамилией уже зарегестрирован',
            'last_name.min' => 'Должно быть минимум 2 символа',
            'email.required' => 'Это поле обязательно для заполнения',
            'email.unique' => 'Пользователи с таким email уже зарегестрирован',
            'password.required' => 'Это поле обязательно для заполнения',
            'password.min' => 'Должно быть минимум 5 символов',
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
        $user = Sentinel::registerAndActivate($credentials);
        $role = Sentinel::findRoleById($request->group);
        $role->users()->attach($user);

        if ($user->save()) {
            return response()->json(['success' => 'Ваша учетная запись успешно создана. Ссылка для активации отправлена на вашу почту']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function edit($id) {
        $user = Sentinel::findById($id);
        $roles = DB::select('select `id`, `name` from `roles`');
       
        return view('admin.user_edit', [
            'user' => $user,
            'roles' => $roles,
            'role_id' => $user->roles()->first()->id
        ]);
    }

    public function postEdit(Request $request) {
        if ($request->password == null) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|alpha|min:2',
                'last_name' => 'required|alpha|min:2',
                'email' => 'required|email',
            ],
            [
                'first_name.required' => 'Это поле обязательно для заполнения',
                'first_name.unique' => 'Пользователи с таким именем уже зарегестрирован',
                'first_name.alpha' => 'Только символы',
                'first_name.min' => 'Должно быть минимум 2 символа',
                'last_name.required' => 'Это поле обязательно для заполнения',
                'last_name.alpha' => 'Только символы',
                'last_name.unique' => 'Пользователи с такой фамилией уже зарегестрирован',
                'last_name.min' => 'Должно быть минимум 2 символа',
                'email.required' => 'Это поле обязательно для заполнения',
            ]);

            $credentials = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
            ];
        } else {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|alpha|min:2',
                'last_name' => 'required|alpha|min:2',
                'email' => 'required|email',
                'password' => 'min: 5',
            ],
            [
                'first_name.required' => 'Это поле обязательно для заполнения',
                'first_name.unique' => 'Пользователи с таким именем уже зарегестрирован',
                'first_name.alpha' => 'Только символы',
                'first_name.min' => 'Должно быть минимум 2 символа',
                'last_name.required' => 'Это поле обязательно для заполнения',
                'last_name.alpha' => 'Только символы',
                'last_name.unique' => 'Пользователи с такой фамилией уже зарегестрирован',
                'last_name.min' => 'Должно быть минимум 2 символа',
                'email.required' => 'Это поле обязательно для заполнения',
                'password.min' => 'Должно быть минимум 5 символов',
            ]);

            $credentials = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password
            ];
        };
        

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $user = Sentinel::findById($request->uid);
        
        if (Sentinel::validForUpdate($user, $credentials)) {
            $user = Sentinel::update($user, $credentials);

            $curRole = DB::select('select `role_id`
                from `role_users`
                where
                `user_id` = '.$request->uid);

            $lastRole = Sentinel::findRoleById($curRole[0]->role_id);
            $lastRole->users()->detach($user);

            $role = Sentinel::findRoleById($request->group);
            $role->users()->attach($user);
        };
        

        if ($user->save()) {
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function del(Request $request) {
        $user = Sentinel::findById($request->id);

        if ($user->delete()) {
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при удалении']);
        }
    }
}
