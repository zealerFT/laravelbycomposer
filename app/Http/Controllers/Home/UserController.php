<?php

namespace App\Http\Controllers\Home;

use Mail;
use Log;
use DB;
use Illuminate\Http\Request;
use App\Models\Home\Student;
use App\Http\Controllers\Controller;
use App\Models\Home\User;
use App\Jobs\SendEmail;

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

    /**
     * 发送邮件 队列
     * @param $receiver string 接收人
     * @param $subject string 主题（邮件标题）
     * @param string $data mix 邮件正文
     * @param null $attach string 附件地址
     * @param $mailType string 邮件类型
     */
    public function emailqueue()
    {
        $date = date('Y-m-d');
        $user = new User();
        $info = $user->getinfobybirthday($date);
        if (!empty($info)) {
          $data = array('view' => 'Home.User.user', 'view_data' => array('name' => $info['name']));
          $url = public_path('images\zoo.jpg');
          dispatch(new SendEmail($info['email'], '邮件队列测试', $data, $url));
          \Log::info("邮件发送队列创建成功");
        } else {
          \Log::info("邮件发送队列创建失败");
        }

    }

    /**
     * @brief 发送生日祝福短信
     *
     * @return
     */
    public function sendBlessing()
    {
        $date = date('md');
        // $date = 0825;
        $TaStatisticsApi = new TaStatisticsApi();
        $accounts = $TaStatisticsApi->GetAccountsByDate($date);
        if ($accounts['stateCode'] != '00000') {
            return Log::warning($accounts['message']);
        }

        if (!$accounts['accounts']) {
            return Log::info('今天无会员生日！');
        }
        $users = DB::connection('mysql2')->table('user')->whereIn('account', $accounts['accounts'])->select('name','mobile','created_at')->get();
        if ($users) {
            foreach ($users as $value) {
                $data['name'] = $value->name ? $value->name : '用户';
                $timeStamp = time() - strtotime($value->created_at);
                $data['day'] = ceil($timeStamp / 86400);
                $this->dispatch(new SendSMSJob($value->mobile, 'birth_notify', $data));
            }
        }
    }
}
