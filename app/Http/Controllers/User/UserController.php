<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TaskStatus;
use DB;
use Sentinel;

class UserController extends Controller
{
    public function index() {
        $statuses = TaskStatus::all();

        $tasks = DB::select('select `t`.`id`, `t`.`name`, `p`.`name` as `project`, 
            `ts`.`name` as `status`, `u`.`first_name`, `u`.`last_name`
            from `tasks` `t`
            left join `projects` `p` on `p`.`id` = `t`.`project_id`
            left join `task_statuses` `ts` on `ts`.`id` = `t`.`task_status_id`
            left join `users` `u` on `u`.`id` = `t`.`user_id`
            left join `user_projects` `up` on `up`.`project_id` = `p`.`id`
            where
            `p`.`deleted_at` is null and
            `t`.`deleted_at` is null and
            `t`.`user_id` = '.Sentinel::getUser()->id.' and
            `up`.`user_id` = '.Sentinel::getUser()->id);

        return view('user.dashboard', [
            'statuses' => $statuses,
            'tasks' => $tasks
        ]);
    }
}
