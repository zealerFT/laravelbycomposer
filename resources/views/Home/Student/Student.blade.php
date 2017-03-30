@extends('Common.Base')
@section('title', 'feiteng')
@section('content')
  @include('Common.Message')
  <!-- 自定义内容区域 -->
  <div class="panel panel-default">
      <div class="panel-heading">{{$now}}</div>
      <table class="table table-striped table-hover table-responsive">
          <thead>
          <tr>
              <th>ID</th>
              <th>姓名</th>
              <th>年龄</th>
              <th>性别</th>
              <th>添加时间</th>
              <th width="120">操作</th>
          </tr>
          </thead>
          <tbody>
            @foreach ($students as $key => $student)
              <tr>
                  <th scope="row">{{ $student->id }}</th>
                  <td>{{ $student->name }}</td>
                  <td>{{ $student->age }}</td>
                  <td>{{ $student->getsex($student->sex) }}</td>
                  <td>{{ date('Y-m-d', $student->created_at) }}</td>
                  <td>
                      <a href="{{ url('student/detail', ['id' => $student->id]) }}">详情</a>
                      <a href="{{ url('student/update', ['id' => $student->id]) }}">修改</a>
                      <a href="">删除</a>
                  </td>
              </tr>
            @endforeach
          </tbody>
      </table>
  </div>

  <!-- 分页  -->
  <div>
      <ul class="pagination pull-right">
        {{ $students->render() }}
      </ul>
  </div>
@endsection
