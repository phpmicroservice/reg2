<?php

namespace App\Controller;

use Hyperf\Event\Contract\ListenerInterface;

/**
 * Description of WorkStart
 * 进程启动的时候
 * @author dongasai
 */
class WorkStart implements ListenerInterface
{

    public function listen(): array
    {
        // 返回一个该监听器要监听的事件数组，可以同时监听多个事件
        return [
            \Hyperf\Framework\Event\AfterWorkerStart::class
        ];
    }

    /**
     * @param UserRegistered $event
     */
    public function process(object $event)
    {
        $GLOBALS['GAME_ROOT'] = '/var/www/html/server/';

    }

}
