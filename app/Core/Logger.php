<?php

namespace App\Core;

use Hyperf\Cache\CacheManager;
use Psr\Log\LoggerInterface;

/**
 * 使用框架的 日志组件，静态调用，便于输出和记录日志
 */
class Logger
{

    /**
     * @var LoggerInterface $loggerDriver
     */
    public static $loggerDriver;

    static public function info($message, array $context = array())
    {
        $cache = self::getDriver();

        return $cache->info($message, $context);
    }


    static public function file()
    {
        $driver = self::getDriver();
        /**
         * @var \Monolog\Handler\RotatingFileHandler $hander
         */
        $hander = $driver->getHandlers()[0];
        $url    = $hander->getUrl();

        return $url;
    }

    static public function debug($message, array $context = array())
    {
        $cache = self::getDriver();

        return $cache->debug($message, $context);
    }


    /**
     * @return \Hyperf\Logger\Logger
     */
    public static function getDriver()
    {
        if (self::$loggerDriver) {
            return self::$loggerDriver;
        }
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        /**
         * @var \Hyperf\Logger\LoggerFactory $service
         */
        $service = $container->get(\Hyperf\Logger\LoggerFactory::class);

        self::$loggerDriver = $service->get();

        return self::$loggerDriver;
    }

}