<?php

namespace App\Helper;

class Demo
{

    /**
     * 随机报错！
     * @param int $value
     * @throws \Exception
     */
    static public function randEx($value = 80)
    {
        $va = mt_rand(1, 100);
        dump($va);
        if ($va > $value) {
            throw new \Exception("这是一个错误，坑的你哇哇叫！");
        }
    }

}