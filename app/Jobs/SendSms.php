<?php

namespace App\Jobs;

use iscms\Alisms\SendsmsPusher as Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $phone;
    private $title;
    private $content;
    private $code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->phone = $data['phone'];
        $this->title = '费腾博客';
        $this->content = $data['content'];
        $this->code = 'SMS_60025392';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms = new Sms();
        $result = $sms->send($this->phone, $this->title, $this->content, $this->code);
        if ($result->result->success == 1) {
            \Log::info('生日短信队列执行成功！');
        } else {
            \Log::info('生日短信队列执行失败：'.$result->result->msg);
        }
    }
}
