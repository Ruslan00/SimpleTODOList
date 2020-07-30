@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Редактирование статуса задачи
        </div>
        <div class="card-body">
            <div class="alert alert-danger" id="error" style="display: none" role="alert">
            
            </div>
            <form id="editStatusForm" style="text-align: left;">
                {{ csrf_field() }}
                <input type="hidden" name="sid" id="sid" value="{{$status->id}}">
                <div class="form-group">
                    <label for="name">Название *</label>
                    <input type="text" class="form-control" id="name" aria-describedby="emailHelp" value="{{$status->name}}" required>
                    <span id="nameError" style="color: red; display: none" class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                
                
                <button type="submit" class="btn btn-primary btn-submit">Сохранить</button>
                <a href="/admin/task-status" class="btn btn-default">Назад</a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('document').ready(function () {
            $("#editStatusForm").on('submit', function(e){
                e.preventDefault()
                
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/admin/task-status/edit',
                    data: {
                        'name': $('#name').val(),
                        'id': $('#sid').val(),
                    },
                    success:function(response){
                        if (response.error) {
                            if (response.error.name) {
                                $('#nameError').css('display', 'block').html(response.error.name[0])
                            }; 
                        }
                        
                        if (response.success) {
                            window.location = '/admin/task-status'
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