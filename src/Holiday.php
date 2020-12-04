<?php
/**
 * Created by PhpStorm.
 * User: jayding
 * Date: 2020/12/4
 * Time: 20:39
 */

namespace Holiday;


class Holiday
{
    public function __construct()
    {
    }

    public function check()
    {
        return '今天是 星期' . date('N', time());
    }

}