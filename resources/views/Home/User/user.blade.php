@extends('Common.Base')
@section('title', 'feiteng')
@section('content')
  <!-- 自定义内容区域 -->
  @foreach ($birthday as $key => $value)
    {{$value['name']}}
  @endforeach
@endsection
