<?php

namespace App\Http\Controllers\Home;

use Mail;
use Log;
use DB;
use Event;
use Storage;
use Illuminate\Http\Request;
use App\Models\Home\Student;
use App\Http\Controllers\Controller;
use App\Models\Home\User;
use App\Jobs\SendEmail;
use App\Events\SendSms;
use Illuminate\Support\Facades\Redis;

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
        // Mail::raw('邮件测试内容', function ($m) use ($info) {
        //     $m->from('ft910310@qq.com', '费腾fermi');
        //     $m->subject('邮件主题：这是一条测试邮件！');
        //     $m->to($info['email']);
        // });
        $user = DB::table('users')->where('birthday', $date)->first();
        Mail::send('Home.user.reminder', ['name' => $user->name], function ($m) use ($user) {
            $m->from('ft910310@qq.com', 'Your Application');
            $m->to($user->email, $user->name)->subject('Your Reminder!');
        });
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
            $url = public_path('images/zoo.jpg');
            dispatch(new SendEmail($info['email'], '邮件队列测试', $data, $url));
            \Log::info("邮件发送队列创建成功");
        } else {
            \Log::info("邮件发送队列创建失败");
        }
    }

    public function smsevent()
    {
        $date = date('Y-m-d');
        $user = new User();
        $info = $user->getinfobybirthday($date);
        if (!empty($info)) {
            $info['content'] = json_encode(array('name' => $info['name'], 'birthday' => $info['birthday']));
            Event::fire(new SendSMS($info));
            //event(new SendSMS($info));
        } else {
            \Log::info("短信监听事件失败：今天没有人过生日！");
        }
    }

   /**
    * redis缓存生成
    * @return boolean [description]
    */
    public function cacheredis()
    {
        //Redis::set('name', 'Taylor');
        $user = new Student();
        $data = $user->getstudentlist();
        $redis = Redis::connection('default');
        $redis->set("name", "Redis first");
        $redis->lpush("cache-list", "Redis");
        $redis->lpush("cache-list", "Mongodb");
        $redis->lpush("cache-list", "Mysql");
        $redis->lpush("cache-list", "memcached");
        foreach ($data as $key => $value) {
            $redis->lpush('my-list', $value->name);
        }
    }

   /**
    * redis缓存取值
    * @return boolean LOG
    */
    public function getcacheredis()
    {
        $value1 = Redis::get('name');
        $value2 = Redis::lrange('cache-list', 0, 3);
        $value2 = json_encode($value2);
        $value3 = Redis::command('lrange', ['my-list', 4, 10]);
        $value3 = json_encode($value3);
        \Log::info('redis缓存测试:name的值=>'.$value1.'  cache-list的值=>'.$value2.'  my-list的值=>'.$value3);
        //\Log::info('redis缓存测试:name的值=>'.$value1.'  names的值=>'.$value2);
    }

    /**
     * 文件上传测试
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function upload(Request $request)
    {
        if ($request->method() == 'POST') {
            $path = $request->file('avatar')->store('feiteng/avatars');
            $path = Storage::putFile('feiteng/avatars', $request->file('avatar'));
            //指定文件名
            $path = $request->file('avatar')->storeAs(
                'avatars', $request->user()->id
            );
            $path = Storage::putFileAs(
                'avatars', $request->file('avatar'), $request->user()->id
            );
            //指定磁盘
            $path = $request->file('avatar')->store(
                'avatars/'.$request->user()->id, 's3'
            );
            $path = $request->file('avatar')->store('feiteng/avatars');
        } else {
            return view('Home.User.upload');
        }
    }

    /**
     * 七牛文件上传案例
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function qiniuupload(Request $request)
    {
        if ($request->method() == 'POST') {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                //源文件名
                $originname = $file->getClientOriginalName();
                //扩展名
                $ext = $file->getClientOriginalExtension();
                //类型
                $extends = $file->getClientMimeType();
                //临时文件地址
                $realfile = $file->getRealPath();
                //组装新文件名
                $filename = date('Y-m-d').'_'.uniqid().'.'.$ext;
                //获取七牛对象执行上传
                $disk = Storage::disk('qiniu');
                $result = $disk->put($filename, file_get_contents($realfile));
                $dd = $disk->downloadUrl('2-GUI.png');
                var_dump($dd);
                exit;
                if ($result) {
                    \Log::info('文件上传到七牛服务器成功：临时文件资源名->'.$realfile.'   重命名文件->'.$filename);
                } else {
                    \Log::info('文件上传到七牛服务器失败');
                }
            }
        } else {
            return view('Home.User.upload');
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
        $users = DB::connection('mysql2')->table('user')->whereIn('account', $accounts['accounts'])->select('name', 'mobile', 'created_at')->get();
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
