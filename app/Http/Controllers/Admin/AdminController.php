<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TaskStatus;
use App\Task;
use DB;

class AdminController extends Controller
{
    public function index() {
        $statuses = TaskStatus::all();

        $tasks = DB::select('select `t`.`id`, `t`.`name`, `p`.`name` as `project`, 
            `ts`.`name` as `status`, `u`.`first_name`, `u`.`last_name`
            from `tasks` `t`
            left join `projects` `p` on `p`.`id` = `t`.`project_id`
            left join `task_statuses` `ts` on `ts`.`id` = `t`.`task_status_id`
            left join `users` `u` on `u`.`id` = `t`.`user_id`
            where
            `p`.`deleted_at` is null and
            `t`.`deleted_at` is null');

        return view('admin.dashboard', [
            'statuses' => $statuses,
            'tasks' => $tasks
        ]);
    }
}
