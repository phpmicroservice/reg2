<?php

namespace App\Helper;

use App\Core\Logger;
use App\Core\UidBind;

class Sender
{

    /**
     * 发送数据给客户端
     *
     * @param $fd
     * @param $server
     * @param $router
     * @param $data
     */
    static public function send2fd($fd, $server, $router, $data)
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        /**
         * @var \Hyperf\WebSocketServer\Sender $sender
         */
        $sender = $container->get(\Hyperf\WebSocketServer\Sender::class);
        if (!$sender->check($fd)) {
            return false;
        }

        return $sender->push($fd, \App\Core\Data::assemblyS($server, $router, $data));
    }

    /**
     * 发送数据给用户
     *
     * @param $fd
     * @param $server
     * @param $router
     * @param $data
     */
    static public function send2uid($uid, $server, $router, $data)
    {
        $fd = UidBind::getFd4Uid($uid);

        return self::send2fd($fd, $server, $router, $data);
    }

}