@extends('layouts.app')

@section('content')
    <div class="col-md-6" style="margin: 0 auto;">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                @foreach($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
                </ul>
            </div>
        @endif
        <form id="regForm" style="text-align: left;" action="" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="password">Пароль *</label>
                <input type="password" class="form-control" name="password" required>
                <span id="PasswordError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля *</label>
                <input type="password" class="form-control" name="password_confirmation" required>
                <span id="PasswordConfirmError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            
            <button type="submit" class="btn btn-primary btn-submit">Сохранить</button>
        </form>
    </div>
@endsection

@section('script')
    
@endsection