<?php

namespace App\Models\Home;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;  //指定是否模型应该被戳记时间。

    public function getinfobybirthday($day)
    {
        $info = $this->where('birthday', $day)->first();
        if (!empty($info)) {
            return $info->toArray();
        } else {
            return $info;
        }
    }
}
