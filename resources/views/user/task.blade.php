@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Задачи <a href="/user/tasks/add" class="btn btn-success"><i class="fa fa-plus"></i></a>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Название</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Проект</th>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tasks) > 0)
                        @foreach($tasks as $task)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$task->name}}</td>
                            <td>{{$task->status}}</td>
                            <td>{{$task->project}}</td>
                            <td>{{$task->first_name}} {{$task->last_name}}</td>
                            <td>
                                <a href="/user/tasks/edit/{{$task->id}}" class="btn btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="del({{$task->id}})" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="5" style="text-align: center;">Задачи отсутствуют</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function del(id) {
            if (confirm("Вы действительно хотите удалить эту задачу?")) {
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/user/tasks/del',
                    data: {
                        'id': id,
                    },
                    success:function(response){
                        if (response.success) {
                            window.location = '/user/tasks'
                        }; 
                    }
                });
            }
        }
    </script>
@endsection