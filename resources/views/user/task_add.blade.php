@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Создание задачи
        </div>
        <div class="card-body">
            <div class="alert alert-danger" id="error" style="display: none" role="alert">
            
            </div>
            <form id="addProjectForm" style="text-align: left;">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name">Название *</label>
                    <input type="text" class="form-control" id="name" aria-describedby="emailHelp" required>
                    <span id="nameError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

                <div class="form-group">
                    <label for="project">Проект</label>
                    <select name="project" id="project" class="form-control">
                        @foreach($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Статус</label>
                    <select name="status" id="status" class="form-control">
                        @foreach($statuses as $status)
                        <option value="{{$status->id}}">{{$status->name}}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-submit">Создать</button>
                <a href="/user/tasks" class="btn btn-default">Назад</a>
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
                    url:'/user/tasks/add',
                    data: {
                        'name': $('#name').val(),
                        'pid': $('#project option:selected').val(),
                        'sid': $('#status option:selected').val(),
                    },
                    success:function(response){
                        if (response.error) {
                            if (response.error.name) {
                                $('#nameError').css('display', 'block').html(response.error.name[0])
                            }; 
                        }
                        
                        if (response.success) {
                            window.location = '/user/tasks'
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