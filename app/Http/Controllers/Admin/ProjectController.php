<?php

namespace App\Http\Controllers\Admin;

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
        $projects = Project::all();

        if (count($projects) > 0) {
            foreach ($projects as $k => $p) {
                $tasks = DB::select('select `id`, `name`
                    from `tasks`
                    where
                    `project_id` = '.$p->id);

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
        
        return view('admin.project', [
            'projects' => $projects2
        ]);
    }

    public function add() {
        $users = User::where('id', '!=', Sentinel::getUser()->id)->get();

        return view('admin.project_add', [
            'users' => $users
        ]);
    }

    public function postAdd(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|unique:projects,name,NULL,id,deleted_at,NULL',
            'users' => 'required'
        ],
        [
            'name.required' => 'Это поле обязательно для заполнения',
            'name.unique' => 'Проект уже существует',
            'name.min' => 'Должно быть минимум 2 символа',
            'users.required' => 'Это поле обязательно для заполнения',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $project = new Project();
        $project->name = $request->name;

        if ($project->save()) {
            
            foreach ($request->users as $user) {
                $uprojects = new UserProject();
                $uprojects->project_id = $project->id;
                $uprojects->user_id = $user['id'];
                $uprojects->save();
            };
            

            return response()->json(['success' => 'Ok']);
        } else {
            return response()->json(['error_add' => 'Ошибка при сохранении']);
        }
    }

    public function edit($id) {
        $project = Project::find($id);
        $users = User::all();

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

        return view('admin.project_edit', [
            'project' => $project,
            'users' => $users_ar
        ]);
    }

    public function postEdit(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'users' => 'required'
        ],
        [
            'name.required' => 'Это поле обязательно для заполнения',
            'name.min' => 'Должно быть минимум 2 символа',
            'users.required' => 'Это поле обязательно для заполнения',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()]);
        }

        $project = Project::find($request->id);
        $project->name = $request->name;

        if ($project->save()) {
            $up = UserProject::where('project_id', '=', $project->id)->delete();
            foreach ($request->users as $user) {
                $uprojects = new UserProject();
                $uprojects->project_id = $project->id;
                $uprojects->user_id = $user['id'];
                $uprojects->save();
            };

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
