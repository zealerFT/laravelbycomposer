<?php

namespace App\Http\Controllers\Home;

use Mail;
use Log;
use DB;
use Illuminate\Http\Request;
use App\Models\Home\Student;
use App\Http\Controllers\Controller;
use App\Models\Home\User;

class UserController extends Controller
{
    /**
    * 发封电子邮件提醒给用户。
    *
    * @param  Request  $request
    * @param  int  $id
    * @return Response
    */
    public function sendEmailReminder()
    {
        $date = date('Y-m-d');
        $user = new User();
        $info = $user->getinfobybirthday($date);
        //return view('Home.User.user', compact('birthday'));
        Mail::raw('邮件测试内容', function ($m) use ($info) {
            $m->from('ft910310@qq.com', '费腾fermi');
            $m->subject('邮件主题：这是一条测试邮件！');
            $m->to($info['email']);
        });
        // $user = DB::table('users')->where('birthday', $date)->first();
        // Mail::send('Home.user.reminder', ['name' => $user->name], function ($m) use ($user) {
        //     $m->from('ft910310@qq.com', 'Your Application');
        //     $m->to($user->email, $user->name)->subject('Your Reminder!');
        // });
    }
}
