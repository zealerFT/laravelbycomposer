<?php

namespace App\Listeners;

use App\Events\SendSms;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use iscms\Alisms\SendsmsPusher as SMS;

class SendSmsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendSms  $event
     * @return void
     */
    public function handle(SendSms $event)
    {
        $data = $event->data;
        $sms = new SMS();
        $result = $sms->send($data['phone'], '费腾博客', $data['content'], 'SMS_60025392');
        if ($result->result->success == 1) {
            \Log::info('短信监听事件执行成功！');
        } else {
            \Log::info('短信监听事件执行失败：'.$result->result->msg);
        }
    }
}
