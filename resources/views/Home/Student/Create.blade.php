@extends('Common.Base')
@section('title', 'create')
@section('content')
  @include('Common.Createmsg')
  <div class="panel panel-default">
    <div class="panel-heading">新增学生</div>
    <div class="panel-body">
      @include('Home.Student.Form')
    </div>
</div>
@endsection
