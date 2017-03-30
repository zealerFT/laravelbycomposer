<?php

namespace App\Models\Home;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    const SEX_BOY = 1;
    const SEX_GRIL = 0;
    const SEX_UN = 2;
    protected $table = 'student';
    protected $fillable = ['name', 'age', 'sex'];
    //默认使用时间戳功能，当我们在数据库中创建 create_at updated_at 字段的时候，laravel会自动帮我们生成响应的时间戳
    public $timestamps = true;

     /**
      * 禁止时间戳转换为字符串时间
      * @return [type] [description]
      */
     protected function getDateFormat()
     {
         return time();
     }

     /**
      * select的时候可以避免时间转换成字符串时间
      */
     protected function asDateTime($val)
     {
         return $val;
     }

    public function getsex($key = '')
    {
        $arr = [
         self::SEX_UN => '未知',
         self::SEX_BOY => '男',
         self::SEX_GRIL => '女',
       ];
        if ($key !== '') {
            return array_key_exists($key, $arr)?$arr[$key]:$arr[self::SEX_UN];
        }
        return $arr;
    }
}
