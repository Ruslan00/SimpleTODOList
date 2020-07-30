@extends('layouts.cabinet')

@section('content')
    <div class="csrd">
        <div class="card-header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                
                
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Фильтр
                    </a>
                    <div class="dropdown-menu">
                    <form class="px-4 py-3" id="filterForm">
                        @foreach ($statuses as $status)
                        
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="{{$status->id}}">
                                <label class="form-check-label" for="{{$status->id}}">{{$status->name}}</label>
                            </div>
                        
                        @endforeach
                        <button type="submit" class="btn btn-primary">Применить</button>
                    </form>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li> -->
                </ul>
                <form class="form-inline my-2 my-lg-0" id="searchForm">
                <input class="form-control mr-sm-2" type="search" id="search" placeholder="Поиск" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>
                </form>
            </div>
            </nav>
            <div class="card-body">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Название</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Проект</th>
                            <th scope="col">Пользователь</th>
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
    </div>
@endsection

@section('script')
    <script>
        $('document').ready(function () {
            $("#filterForm").on('submit', function(e){
                e.preventDefault()
                var myObjects = [{}];
                $('input:checkbox:checked').each(function(){
                    myObjects.push({
                        'id' : $(this).attr('id'),
                        'checked' : $(this).val()
                    });
                });
                var arr = myObjects.splice(1)
                
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/user/tasks/filter',
                    data: {
                        'arr': JSON.stringify(arr)
                    },
                    success:function(response){
                        var tasks = response.tasks;
            
                        $('.table tbody tr').remove();
                        var isJqueryObject = tasks[0][0] instanceof Object;
                        
                        if (isJqueryObject) {
                            for (var i = 0; i < tasks.length; i++) {
                                for (var j = 0; j < tasks[i].length; j++) {
                                    $('.table tbody').append('<tr>'+
                                        '<td>'+(i+1)+'</td>'+
                                        '<td>'+tasks[i][j].name+'</td>'+
                                        '<td>'+tasks[i][j].status+'</td>'+
                                        '<td>'+tasks[i][j].project+'</td>'+
                                        '<td>'+tasks[i][j].first_name+' '+tasks[i][j].last_name+'</td>'+
                                        '</tr>');
                                }
                            }
                        } else {
                            $('.table tbody').append('<tr>'+
                                '<td colspan="5" style="text-align: center;">Задачи не найдены</td>'+
                                '</tr>');
                        };
                        
                    }
                });
            });

            $("#searchForm").on('submit', function(e){
                e.preventDefault()
                
                $.ajax({
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/user/tasks/search',
                    data: {
                        'search': $('#search').val()
                    },
                    success:function(response){
                        var tasks = response.tasks;
            
                        $('.table tbody tr').remove();
                        var isJqueryObject = tasks[0] instanceof Object;
                        
                        if (isJqueryObject) {
                            for (var j = 0; j < tasks.length; j++) {
                                $('.table tbody').append('<tr>'+
                                    '<td>'+(j+1)+'</td>'+
                                    '<td>'+tasks[j].name+'</td>'+
                                    '<td>'+tasks[j].status+'</td>'+
                                    '<td>'+tasks[j].project+'</td>'+
                                    '<td>'+tasks[j].first_name+' '+tasks[j].last_name+'</td>'+
                                    '</tr>');
                            }
                        } else {
                            $('.table tbody').append('<tr>'+
                                '<td colspan="5" style="text-align: center;">Задачи не найдены</td>'+
                                '</tr>');
                        };
                        
                    }
                });
            });
        })
   
        
    </script>
@endsection