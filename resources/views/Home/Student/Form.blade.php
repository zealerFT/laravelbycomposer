<form class="form-horizontal" method="post" name="Student" action=''>
  {{csrf_field()}}
  {{-- {{method_field('put')}} --}}
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">姓名</label>

        <div class="col-sm-5">
            <input type="text" name="name" class="form-control" value="{{ old('name') ? old('name') : $student->name }}" id="name" placeholder="请输入学生姓名">
        </div>
        <div class="col-sm-5">
            <p class="form-control-static text-danger">{{ $errors->first('name') }}</p>
        </div>
    </div>
    <div class="form-group">
        <label for="age" class="col-sm-2 control-label">年龄</label>

        <div class="col-sm-5">
            <input type="text" name="age" class="form-control" value="{{ old('age') ? old('age') : $student->age }}" id="age" placeholder="请输入学生年龄">
        </div>
        <div class="col-sm-5">
            <p class="form-control-static text-danger">{{ $errors->first('age') }}</p>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">性别</label>

        <div class="col-sm-5">
          @foreach ($student->getsex() as $key => $value)
            <label class="radio-inline">
              @if (isset($student->sex) && ($student->sex == $key))
                <input type="radio" name="sex"  checked value="{{ $key }}"> {{ $value }}
              @else
                <input type="radio" name="sex"  value="{{ $key }}"> {{ $value }}
              @endif

            </label>
          @endforeach
        </div>
        <div class="col-sm-5">
            <p class="form-control-static text-danger">{{ $errors->first('sex') }}</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">提交</button>
        </div>
    </div>
</form>
