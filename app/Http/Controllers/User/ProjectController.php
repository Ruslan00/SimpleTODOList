<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Project;
use App\UserProject;
use App\Task;
use App\User;
use Validator;
use Sentinel;
use DB;

class ProjectController extends Controller
{
    public function index() {
        $projects = DB::select('select `p`.*
            from `projects` `p`
            left join `user_projects` `up` on `up`.`project_id` = `p`.`id`
            where
            `p`.`deleted_at` is null and
            `up`.`user_id` = '.Sentinel::getUser()->id);

        if (count($projects) > 0) {
            foreach ($projects as $k => $p) {
                $tasks = DB::select('select `id`, `name`
                    from `tasks`
                    where
                    `project_id` = '.$p->id.' and
                    `user_id` = '.Sentinel::getUser()->id);

                $projects2[$k] = [
                    'id' => $p->id,
                    'name' => $p->name
                ];
                if (count($tasks) > 0) {
                    foreach ($tasks as $t) {
                        $projects2[$k]['tasks'][] = array(
                            'id' => $t->id,
                            'name' => $t->name
                        );
                    }
                } else {
                    $projects2[$k]['tasks'] = [];
                }
                
            }
        } else {
            $projects2 = [];
        }
        
        return view('user.project', [
            'projects' => $projects2
        ]);
    }

    public function add() {
        $users = User::where('id', '!=', Sentinel::getUser()->id)->get();

        return view('user.project_add', [
            'users' => $users
        ]);
    }

    public function postAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|unique:projects,name,NULL,id,deleted_at,NULL',
        ],
        [
            'name.required' => 'Это поле обязательно для заполнения',
            'name.unique' => 'Проект уже существует',
            'name.min' => 'Должно быть минимум 2 символа',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $project = new Project();
        $project->name = $request->name;

        if ($project->save()) {
            $uprojects = new UserProject();
            $uprojects->project_id = $project->id;
            $uprojects->user_id = Sentinel::getUser()->id;
            $uprojects->save();
            
            if ($request->users) {
                foreach ($request->users as $user) {
                    $uprojects2 = new UserProject();
                    $uprojects2->project_id = $project->id;
                    $uprojects2->user_id = $user['id'];
                    $uprojects2->save();
                };
            }
            
            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function edit($id) {
        $project = Project::find($id);
        $users = User::where('id', '!=', Sentinel::getUser()->id)->get();

        foreach ($users as $u) {
            $uprojects = UserProject::where('project_id', '=', $id)
                ->where('user_id', '=', $u->id)
                ->first();

            if ($uprojects) {
                $users_ar[] =  [
                    'id' => $u->id,
                    'first_name' => $u->first_name,
                    'last_name' => $u->last_name,
                    'check' => 1
                ];
            } else {
                $users_ar[] =  [
                    'id' => $u->id,
                    'first_name' => $u->first_name,
                    'last_name' => $u->last_name,
                    'check' => 0
                ];
            };

            
        };

        return view('user.project_edit', [
            'project' => $project,
            'users' => $users_ar
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

        $project = Project::find($request->id);
        $project->name = $request->name;

        
        if ($project->save()) {
            $up = UserProject::where('project_id', '=', $project->id)->delete();
            $uprojects = new UserProject();
            $uprojects->project_id = $project->id;
            $uprojects->user_id = Sentinel::getUser()->id;
            $uprojects->save();

            if ($request->users) {
                foreach ($request->users as $user) {
                    $uprojects = new UserProject();
                    $uprojects->project_id = $project->id;
                    $uprojects->user_id = $user['id'];
                    $uprojects->save();
                };
            }

            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function del(Request $request) {
        $task = Task::where('project_id', '=', $request->id)
            ->whereNull('deleted_at')
            ->first();

        if ($task) {
            return response()->json(['error_del' => 'Невозможно удалить проект, т.к. в нем есть задачи']);
        };  

        $project = Project::find($request->id);

        if ($project->delete()) {
            $uprojects = UserProject::where('project_id', '=', $request->id)->delete();

            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_del' => 'Ошибка при удалении']);
        }
    }
}
