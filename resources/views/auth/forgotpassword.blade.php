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
                <label for="email">Email *</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" required>
                <span id="EmailError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            
            <button type="submit" class="btn btn-primary btn-submit">Восстановить</button>
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
                    url:'/forgot-password',
                    data: {
                        'email': $('#email').val(),
                    },
                    success:function(response){
                        if (response.success) {
                            $('#success').css('display', 'block').html(response.success)
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