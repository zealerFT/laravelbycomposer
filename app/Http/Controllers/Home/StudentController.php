<?php

namespace App\Http\Controllers\Home;

use Request;
use Lang;
use Session;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use App\Models\Home\Student;
use Carbon\Carbon;

class StudentController extends Controller
{

  /**
   * 首页
   */
  public function index()
  {
      $students = Student::paginate('5');
      $now = Carbon::now();
      return view('Home.Student.Student', compact('students', 'now'));
  }

  /**
   * 新增页面
   * @return blade
   */
  public function create()
  {
      $student = new Student();
      if (Request::method() == 'POST') {
          //$data = Request::input();
      $data = Request::all();
          $messages = [
        'name.required' => '姓名不能为空！',
        'age.required' => '年龄不能为空！',
        'sex.required' => '请选择一个性别！',
        'min' => ':attribute 最小长度为2个字或字母数字',
        'max' => ':attribute 最大长度为20个字或字母数字',
        'integer' => ':attribute 整型',
        'name' => '姓名',
        'age' => '年龄',
        'sex' => '性别'
      ];
          $validator = Validator::make($data, [
        'name' => 'required|min:2|max:20',
        'age'  => 'required|integer',
        'sex'  => 'required|integer'
      ], $messages
      );
          if ($validator->fails()) {
              return redirect('student/create')
                      ->withErrors($validator)
                      ->withInput();
          } else {
              if (Student::create($data)) {
                  return redirect('student/index')->with('success', '操作成功！');
              } else {
                  return redirect()->back()->with('fail', '操作失败！');
              }
          }
      } else {
          return view('Home.Student.Create', [
        'student' => $student
      ]);
      }
  }

  /**
   * 修改
   * @param  $id 主键
   * @return  blade
   */
  public function update($id)
  {
      //$student = new Student();
    $student = Student::find($id);  //获取的是一个对象，所以可以在修改的时候继续使用此对象,而且id就是当前操作对象，所以最后保存有主键
    if (Request::method() == 'POST') {
        $data = Request::input();
        $messages = [
        'name.required' => '姓名不能为空！',
        'age.required' => '年龄不能为空！',
        'sex.required' => '请选择一个性别！',
        'min' => ':attribute 最小长度为2个字或字母数字',
        'max' => ':attribute 最大长度为20个字或字母数字',
        'integer' => ':attribute 整型'
      ];
        $validator = Validator::make($data, [
        'name' => 'required|min:2|max:20',
        'age'  => 'required|integer',
        'sex'  => 'required|integer'
      ], $messages
      );
        if ($validator->fails()) {
            return redirect('student/create')
                      ->withErrors($validator)
                      ->withInput(['student' => $student]);
        } else {
            $student->name = $data['name'];
            $student->age = $data['age'];
            $student->sex = $data['sex'];
            if ($student->save()) {
                return redirect('student/index')->with('success', '修改成功！');
            }
        }
    } else {
        return view('Home.Student.Update', [
        'student' => $student
      ]);
    }
  }


  /**
   * 执行添加
   */
  public function add(Request $reuqest)
  {
      $data = Request::input();
      $student = new Student();
      $student->name = $data['name'];
      $student->age = $data['age'];
      $student->sex = $data['sex'];
      if ($student->save()) {
          $url = route('studentindex');
          return redirect()->route('studentindex');
      } else {
          return redirect()->back();
      }
  }
}
