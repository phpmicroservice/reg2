<?php


namespace App\Listener;


use App\Core\UidBind;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * Class BootApplicationListener
 * 启动应用程序事件，进行一些常量定义等操作
 * @package App\Listener
 */
class BootApplicationListener implements ListenerInterface
{

    public function listen(): array
    {
        return [
            \Hyperf\Framework\Event\BootApplication::class
        ];
    }

    /**
     * @param \Hyperf\Framework\Event\BootApplication $event
     */
    public function process(object $event)
    {
        define('APP_ENV', getenv('APP_ENV'));
        define('APP_DEV', APP_ENV === 'dev');

        UidBind::getDriver()->clear();
    }

}