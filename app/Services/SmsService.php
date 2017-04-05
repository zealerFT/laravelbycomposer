<?php
namespace App\Services;

use iscms\Alisms\SendsmsPusher as SMS;

class SmsServices
{

   /**
    * 发送邮件服务--alidayu
    * @return class
    */
   public function sendSmsByAli($phone, $title, $content, $code)
   {
       $sms = new SMS();
       $result = $sms->send($phone, $title, $content, $code);
       return $result;
   }
}
