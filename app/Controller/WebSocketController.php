<?php

namespace App\Controller;

use App\Core\UidBind;
use App\WSController\Sys\User;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

/**
 * Description of WebSocketController
 *
 * @author dongasai
 */
class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{

    /**
     *
     * @param WebSocketServer $server
     * @param Frame $frame
     * @return void
     */
    public function onMessage($server, Frame $frame): void
    {
//        $this-
        pr('onMessageee', get_class($server), $frame->fd, $frame->data);
        if(APP_DEV){
            usleep(mt_rand(100,500)*1000);
        }
        $dispatcher = new \App\Core\Dispatcher($frame, $server);
        $res        = $dispatcher->dispatch();
        pr('msg- res', $res);
        if ($res) {
            $server->push($frame->fd, $res);
        }


    }

    /**
     * @param WebSocketServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose($server, int $fd, int $reactorId): void
    {
        $uid = UidBind::getUid4Fd($fd);
        UidBind::unBindUid($fd);
        User::distribute($uid,$server);
    }

    public function onOpen($server, Request $request): void
    {
        $server->push($request->fd, json_encode([
                                                    "p" => uniqid(),
                                                    'd' => [],
                                                    's' => 'ws',
                                                    'r' => 'open'
                                                ]));
    }

}
