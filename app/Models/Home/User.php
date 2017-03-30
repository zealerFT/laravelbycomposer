<?php

namespace App\Models\Home;

use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Model
{
    protected $table = 'users';
    //protected $fillable = ['name', 'age', 'sex'];
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

    public function getinfobybirthday($day)
    {
        $info = $this->where('birthday', $day)->get();
        return $info->toArray();
    }

}
