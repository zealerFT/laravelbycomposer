<?php

namespace App\Models\Home;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;  //指定是否模型应该被戳记时间。

   /**
    * 通过日期查询过生的用户信息
    * @param  String $day 日期
    * @return Array or null
    */
    public function getinfobybirthday($day)
    {
        $info = $this->where('birthday', $day)->first();
        if (!empty($info)) {
            return $info->toArray();
        } else {
            return $info;
        }
    }

    /**
     * 获取用户列表
     * @return [type] [description]
     */
    public function getuserlist()
    {
        $info = $this->get();
        return $info->toArray();
    }
}
