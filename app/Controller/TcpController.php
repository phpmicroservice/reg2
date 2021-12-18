<?php

namespace App\Controller;

use Hyperf\Contract\OnReceiveInterface;

/**
 * Tcp 服务的处理
 */
class TcpController implements OnReceiveInterface
{

    /**
     * @param $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     * @return void
     */
    public function onReceive($server, int $fd, int $reactorId, string $data): void
    {
        // TODO: Implement onReceive() method.
    }

}