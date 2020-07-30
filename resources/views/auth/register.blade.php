@extends('layouts.app')

@section('content')
    <div class="col-md-6" style="margin: 0 auto;">
        <div class="alert alert-success" id="success" style="display: none" role="alert">
            
        </div>
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
                <label for="password_confirmation">Подтверждение пароля *</label>
                <input type="password" class="form-control" id="password_confirmation" required>
                <span id="PasswordConfirmError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            
            <button type="submit" class="btn btn-primary btn-submit">Регистрация</button>
        </form>
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
                    url:'/register',
                    data: {
                        'first_name': $('#first_name').val(),
                        'last_name': $('#last_name').val(),
                        'email': $('#email').val(),
                        'password': $('#password').val(),
                        'password_confirmation': $('#password_confirmation').val(),
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
                            $('#success').css('display', 'block').html(response.success)
                            // $('#FirstNameError').css('display', 'none')
                            // $('#LastNameError').css('display', 'none')
                            // $('#EmailError').css('display', 'none')
                            // $('#PasswordError').css('display', 'none')
                            // $('#PasswordConfirmError').css('display', 'none')

                            // $('#first_name').val(''),
                            // $('#last_name').val(''),
                            // $('#email').val(''),
                            // $('#password').val(''),
                            // $('#password_confirmation').val(''),
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