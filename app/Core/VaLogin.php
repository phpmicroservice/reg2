<?php


namespace App\Core;


use App\WSController\Sys\User;

/**
 * 是否登录验证
 *
 */
class VaLogin
{

    /**
     * 验证是否登录
     * @param $fid
     * @return bool
     */
    static public function validateLogin($fid,$HandlerClass,$ActionName)
    {
        if($HandlerClass == User::class && $ActionName == 'login'){
            return true;
        }
        $uid = UidBind::getUid4Fd($fid);

        return !empty($uid);
    }

}