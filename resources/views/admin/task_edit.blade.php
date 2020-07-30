@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Редактирование задачи
        </div>
        <div class="card-body">
            <div class="alert alert-danger" id="error" style="display: none" role="alert">
            
            </div>
            <form id="addProjectForm" style="text-align: left;">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id" value="{{$task->id}}">
                <div class="form-group">
                    <label for="name">Название *</label>
                    <input type="text" class="form-control" id="name" value="{{$task->name}}" aria-describedby="emailHelp" required>
                    <span id="FirstNameError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

                <div class="form-group">
                    <label for="project">Проект</label>
                    <select name="project" id="project" class="form-control">
                        @foreach($projects as $project)
                            @if ($project->id == $task->project_id)
                            <option value="{{$project->id}}" selected>{{$project->name}}</option>
                            @else
                            <option value="{{$project->id}}">{{$project->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Статус</label>
                    <select name="status" id="status" class="form-control">
                        @foreach($statuses as $status)
                            @if ($status->id == $task->task_status_id)
                            <option value="{{$status->id}}" selected>{{$status->name}}</option>
                            @else
                            <option value="{{$status->id}}">{{$status->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="user">Пользователь</label>
                    <select name="user" id="user" class="form-control">
                        @foreach($users as $user)
                            @if ($user->id == $task->user_id)
                            <option value="{{$user->id}}" selected>{{$user->first_name}} {{$user->last_name}}</option>
                            @else
                            <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-submit">Сохранить</button>
                <a href="/admin/tasks" class="btn btn-default">Назад</a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('document').ready(function () {
            $("#addProjectForm").on('submit', function(e){
                e.preventDefault()
                
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/admin/tasks/edit',
                    data: {
                        'id': $('#id').val(),
                        'name': $('#name').val(),
                        'pid': $('#project option:selected').val(),
                        'sid': $('#status option:selected').val(),
                        'uid': $('#user option:selected').val(),
                    },
                    success:function(response){
                        if (response.error) {
                            if (response.error.first_name) {
                                $('#FirstNameError').css('display', 'block').html(response.error.first_name[0])
                            }; 
                            if (response.error.last_name) {
                                $('#LastNameError').css('display', 'block').html(response.error.last_name[0])
                            }; 
                            if (response.error.email) {
                                $('#EmailError').css('display', 'block').html(response.error.email[0])
                            }; 
                            if (response.error.password) {
                                $('#PasswordError').css('display', 'block').html(response.error.password[0])
                            }; 
                            if (response.error.password_confirmation) {
                                $('#PasswordConfirmError').css('display', 'block').html(response.error.password_confirmation[0])
                            }; 
                        }
                        
                        if (response.success) {
                            window.location = '/admin/tasks'
                        }; 
                        if (response.error_add) {
                            $('#error').css('display', 'block').html(response.error_add)
                        }; 
                    }
                });
            });
        })
   
        
    </script>
@endsection