@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Пользователи <a href="/admin/users/add" class="btn btn-success"><i class="fa fa-plus"></i></a>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Фамилия</th>
                        <th scope="col">Email</th>
                        <th scope="col">Группа</th>
                        <th scope="col">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <th scope="row">{{$loop->index + 1}}</th>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->name}}</td>
                        <td>
                            <a href="/admin/users/edit/{{$user->id}}" class="btn btn-success">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="del({{$user->id}})" class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function del(id) {
            if (confirm("Вы действительно хотите удалить этого пользователя?")) {
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/admin/users/del',
                    data: {
                        'id': id,
                    },
                    success:function(response){
                        if (response.success) {
                            window.location = '/admin/users'
                        }; 
                    }
                });
            }
        }
    </script>
@endsection