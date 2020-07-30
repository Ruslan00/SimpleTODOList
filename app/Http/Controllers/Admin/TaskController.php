<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\Task;
use App\Project;
use App\TaskStatus;
use App\User;

class TaskController extends Controller
{
    public function index() {
        $tasks = DB::select('select `t`.`id`, `t`.`name`, `p`.`name` as `project`, 
            `ts`.`name` as `status`, `u`.`first_name`, `u`.`last_name`
            from `tasks` `t`
            left join `projects` `p` on `p`.`id` = `t`.`project_id`
            left join `task_statuses` `ts` on `ts`.`id` = `t`.`task_status_id`
            left join `users` `u` on `u`.`id` = `t`.`user_id`
            where
            `p`.`deleted_at` is null and
            `t`.`deleted_at` is null');
        return view('admin.task', [
            'tasks' => $tasks
        ]);
    }

    public function add() {
        $projects = Project::all();
        $statuses = TaskStatus::all();
        $users = User::all();
        return view('admin.task_add', [
            'projects' => $projects,
            'statuses' => $statuses,
            'users' => $users
        ]);
    }

    public function postAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
        ],
        [
            'name.required' => 'Это поле обязательно для заполнения',
            'name.min' => 'Должно быть минимум 2 символа',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $task = new Task();
        $task->name = $request->name;
        $task->user_id = $request->uid;
        $task->project_id = $request->pid;
        $task->task_status_id = $request->sid;

        if ($task->save()) {
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function edit($id) {
        $task = Task::find($id);
        $projects = Project::all();
        $statuses = TaskStatus::all();
        $users = User::all();

        return view('admin.task_edit', [
            'task' => $task,
            'projects' => $projects,
            'statuses' => $statuses,
            'users' => $users
        ]);
    }

    public function postEdit(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
        ],
        [
            'name.required' => 'Это поле обязательно для заполнения',
            'name.min' => 'Должно быть минимум 2 символа',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $task = Task::find($request->id);
        $task->name = $request->name;
        $task->user_id = $request->uid;
        $task->project_id = $request->pid;
        $task->task_status_id = $request->sid;

        if ($task->save()) {
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function del(Request $request) {
        $task = Task::find($request->id);

        if ($task->delete()) {
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function filter(Request $request) {
        $data = json_decode($request->arr);
        if (count($data) > 0) {
            foreach ($data as $d) {
                $tasks[] = DB::select('select `t`.`id`, `t`.`name`, `p`.`name` as `project`, 
                    `ts`.`name` as `status`, `u`.`first_name`, `u`.`last_name`
                    from `tasks` `t`
                    left join `projects` `p` on `p`.`id` = `t`.`project_id`
                    left join `task_statuses` `ts` on `ts`.`id` = `t`.`task_status_id`
                    left join `users` `u` on `u`.`id` = `t`.`user_id`
                    where
                    `p`.`deleted_at` is null and
                    `t`.`deleted_at` is null and
                    `ts`.`id` = '.$d->id);
            };
        } else {
            $tasks[] = DB::select('select `t`.`id`, `t`.`name`, `p`.`name` as `project`, 
                    `ts`.`name` as `status`, `u`.`first_name`, `u`.`last_name`
                    from `tasks` `t`
                    left join `projects` `p` on `p`.`id` = `t`.`project_id`
                    left join `task_statuses` `ts` on `ts`.`id` = `t`.`task_status_id`
                    left join `users` `u` on `u`.`id` = `t`.`user_id`
                    where
                    `p`.`deleted_at` is null and
                    `t`.`deleted_at` is null ');
        }
        return response()->json(['tasks' => $tasks]);
    }

    public function search(Request $request) {
        $tasks = DB::select("select `t`.`id`, `t`.`name`, `p`.`name` as `project`, 
            `ts`.`name` as `status`, `u`.`first_name`, `u`.`last_name`
            from `tasks` `t`
            left join `projects` `p` on `p`.`id` = `t`.`project_id`
            left join `task_statuses` `ts` on `ts`.`id` = `t`.`task_status_id`
            left join `users` `u` on `u`.`id` = `t`.`user_id`
            where
            `p`.`deleted_at` is null and
            `t`.`deleted_at` is null and
            `t`.`name` LIKE '%" .$request->search."%'");

        return response()->json(['tasks' => $tasks]);
    }
}
