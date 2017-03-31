<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Home\UserController as User;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $filePath = public_path('file/test.txt');
        // 生日祝福邮件
        $schedule->call(function () {
            $Birthemail = new User();
            $Birthemail->emailqueue();
            \Log::info("已执行生日祝福邮件任务调度");
        })->everyMinute();
        // 生日祝福短信
        // $schedule->call(function () {
        //     $BirthSms = new User();
        //     $BirthSms->sendBlessing()->withoutOverlapping();;
        // })->dailyAt('12:00');
        $schedule->call(function () use ($filePath) {
            DB::table('log')->delete();
            \Log::info("已执行删除日志任务调度，发送了文件到相应邮箱：".$filePath);
        })->dailyAt('18:00')
        ->sendOutputTo($filePath)
        ->emailOutputTo('2895217805@qq.com');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
