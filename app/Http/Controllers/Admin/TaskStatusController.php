<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TaskStatus;
use Validator;

class TaskStatusController extends Controller
{
    public function index() {
        $status = TaskStatus::all();
        return view('admin.task-status', [
            'status' => $status
        ]);
    }

    public function add() {
        return view('admin.task-status-add');
    }

    public function postAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2||unique:task_statuses',
        ],
        [
            'name.required' => 'Это поле обязательно для заполнения',
            'name.unique' => 'Статус уже существует',
            'name.min' => 'Должно быть минимум 2 символа',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $status = new TaskStatus();
        $status->name = $request->name;

        if ($status->save()) {
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function edit($id) {
        $status = TaskStatus::find($id);

        return view('admin.task-status-edit', [
            'status' => $status
        ]);
    }

    public function postEdit(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2||unique:task_statuses',
        ],
        [
            'name.required' => 'Это поле обязательно для заполнения',
            'name.unique' => 'Статус уже существует',
            'name.min' => 'Должно быть минимум 2 символа',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $status = TaskStatus::find($request->id);
        $status->name = $request->name;

        if ($status->save()) {
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function del(Request $request) {
        $status = TaskStatus::find($request->id);

        if ($status->delete()) {
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }
}
