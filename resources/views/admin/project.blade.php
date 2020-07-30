@extends('layouts.cabinet')

@section('content')
    <div class="card">
        <div class="card-header">
            Проекты <a href="/admin/projects/add" class="btn btn-success"><i class="fa fa-plus"></i></a>
        </div>

        <div class="card-body">
            <div class="alert alert-danger" id="error" style="display: none" role="alert"></div>
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Название</th>
                        <th scope="col">Задачи</th>
                        <th scope="col">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($projects)
                        @foreach($projects as $p)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$p['name']}}</td>
                                <td>
                                    <ul>
                                        @foreach($p['tasks'] as $p2)
                                        <li>{{$p2['name']}}</li> 
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <a href="/admin/projects/edit/{{$p['id']}}" class="btn btn-success">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="del({{$p['id']}})" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" style="text-align: center;">Проекты отсутствуют</td>
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
            if (confirm("Вы действительно хотите удалить этот проект?")) {
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/admin/projects/del',
                    data: {
                        'id': id,
                    },
                    success:function(response){
                        if (response.success) {
                            window.location = '/admin/projects'
                        };
                        if (response.error_del) {
                            $('#error').css('display', 'block').html(response.error_del)
                        }; 
                    }
                });
            }
        }
    </script>
@endsection