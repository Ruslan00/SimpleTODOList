@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Статусы задач <a href="/admin/task-status/add" class="btn btn-success"><i class="fa fa-plus"></i></a>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Название</th>
                        <th scope="col">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($status) > 0)
                        @foreach($status as $s)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$s->name}}</td>
                            <td>
                                <a href="/admin/task-status/edit/{{$s->id}}" class="btn btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="del({{$s->id}})" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="3" style="text-align: center;">Статусы отсутствуют</td>
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
            if (confirm("Вы действительно хотите удалить этот статус?")) {
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/admin/task-status/del',
                    data: {
                        'id': id,
                    },
                    success:function(response){
                        if (response.success) {
                            window.location = '/admin/task-status'
                        }; 
                    }
                });
            }
        }
    </script>
@endsection