@extends('layouts.app')

@section('content')
    <div class="col-md-6" style="margin: 0 auto;">
        <div class="alert alert-success" id="success" style="display: none" role="alert">
            
        </div>
        <div class="alert alert-danger" id="error" style="display: none" role="alert">
            
        </div>
        <form id="loginForm" style="text-align: left;">
            {{ csrf_field() }}
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
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Запомнить меня</label>
            </div>
            <div class="form-group">
                <a href="/forgot-password">Забыли пароль?</a>
            </div>
            
            <button type="submit" class="btn btn-primary btn-submit">Войти</button>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $('document').ready(function () {
            $("#loginForm").on('submit', function(e){
                e.preventDefault()
                
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/login',
                    data: {
                        'email': $('#email').val(),
                        'password': $('#password').val(),
                        'remember_me': $('#remember').prop('checked')
                    },
                    success:function(response){
                        if (response.success) {
                            // $('#success').css('display', 'block').html(response.success)
                            window.location = '/'+response.slug
                        }; 
                        if (response.error) {
                            $('#error').css('display', 'block').html(response.error)
                        }; 
                    }
                });
            });
        })
   
        
    </script>
@endsection