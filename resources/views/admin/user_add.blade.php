@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Создание пользователя
        </div>
        <div class="card-body">
            <div class="alert alert-danger" id="error" style="display: none" role="alert">
            
            </div>
            <form id="regForm" style="text-align: left;">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="first_name">Имя *</label>
                    <input type="text" class="form-control" id="first_name" aria-describedby="emailHelp" required>
                    <span id="FirstNameError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <label for="last_name">Фамилия *</label>
                    <input type="text" class="form-control" id="last_name" aria-describedby="emailHelp" required>
                    <span id="LastNameError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" required>
                    <span id="EmailError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <label for="password">Пароль *</label>
                    <input type="password" class="form-control" id="password" required>
                    <span id="PasswordError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <label for="group">Группа *</label>
                    <select name="group" id="group" class="form-control">
                        @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
                
                
                <button type="submit" class="btn btn-primary btn-submit">Создать</button>
                <a href="/admin/users" class="btn btn-default">Назад</a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('document').ready(function () {
            $("#regForm").on('submit', function(e){
                e.preventDefault()
                
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/admin/users/add',
                    data: {
                        'first_name': $('#first_name').val(),
                        'last_name': $('#last_name').val(),
                        'email': $('#email').val(),
                        'password': $('#password').val(),
                        'group': $('#group option:selected').val(),
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
                            window.location = '/admin/users'
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