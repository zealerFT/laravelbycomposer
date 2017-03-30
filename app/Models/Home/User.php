<?php

namespace App\Models\Home;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    //protected $fillable = ['name', 'age', 'sex'];
    //public $timestamps = true;
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    // /**
    //  * 禁止时间戳转换为字符串时间
    //  * @return [type] [description]
    //  */
    // protected function getDateFormat()
    // {
    //     return time();
    // }
    //
    // /**
    //  * select的时候可以避免时间转换成字符串时间
    //  */
    // protected function asDateTime($val)
    // {
    //     return $val;
    // }

    public function getinfobybirthday($day)
    {
        $info = $this->where('birthday', $day)->first();
        return $info->toArray();
    }
}
