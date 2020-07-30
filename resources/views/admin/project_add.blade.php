@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Создание проекта
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
                    <label for="name">Пользователи *</label>
                    @foreach ($users as $user)
                        
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="{{$user->id}}">
                            <label class="form-check-label" for="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</label>
                        </div>
                    
                    @endforeach
                    <span id="usersError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                
                
                <button type="submit" class="btn btn-primary btn-submit">Создать</button>
                <a href="/admin/projects" class="btn btn-default">Назад</a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('document').ready(function () {
            $("#addProjectForm").on('submit', function(e){
                e.preventDefault()
                var myObjects = [{}];
                $('input:checkbox:checked').each(function(){
                    myObjects.push({
                        'id' : $(this).attr('id'),
                        'checked' : $(this).val()
                    });
                });
                
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/admin/projects/add',
                    data: {
                        'name': $('#name').val(),
                        'users': myObjects.splice(1)
                    },
                    success:function(response){
                        if (response.error) {
                            if (response.error.name) {
                                $('#nameError').css('display', 'block').html(response.error.name[0])
                            }; 
                            if (response.error.users) {
                                $('#usersError').css('display', 'block').html(response.error.users[0])
                            }; 
                        }
                        
                        if (response.success) {
                            window.location = '/admin/projects'
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