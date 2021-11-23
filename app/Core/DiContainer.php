<?php


namespace App\Core;

/**
 * Class DiContainer
 * 依赖注入容器助手
 * @package App\Core
 */
class DiContainer
{

    /**
     * @return \Hyperf\WebSocketServer\Sender
     */
    static public function getSender()
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();

        return $container->get(\Hyperf\WebSocketServer\Sender::class);
    }

}