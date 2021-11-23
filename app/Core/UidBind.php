<?php


namespace App\Core;


use Hyperf\Cache\CacheManager;

/**
 * uid fd绑定封装
 */
class UidBind
{

    /**
     * @var \Hyperf\Cache\Driver\DriverInterface $cacheDriver
     */
    public static $cacheDriver;

    /**
     * 绑定 fd 到给 uid
     *
     * @param int $fd
     * @param int $uid
     * @param int $type
     */
    public static function bindUid(int $fd, int $uid, int $type = 0)
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        /**
         * @var \Hyperf\WebSocketServer\Sender $sender
         */
        $sender = $container->get(\Hyperf\WebSocketServer\Sender::class);

        $key      = self::getUidKey($uid);
        $cache    = self::getDriver();
        $fds      = $cache->get($key, []);
        $fds[$fd] = $type;
        $fdsTypes = [];
        $fds3     = [];
        foreach ($fds['fds'] as $fd2) {
            if ($sender->check($fd2)) {
                $fds3[] = $fd2;
            }
        }
        $fds3[] = $fd;
        foreach ($fds['types'] as $fd3 => $type2) {
            $fdsTypes[] = $fds;

        }
        $fds['fds'] = $fds3;
        $cache->set($key, $fds);
        $key2   = self::getFdKey($fd);
        $uidold = $cache->get($key2, 0);
        if ($uidold != $uid) {
            $cache->set($key2, $uid);
        }
    }

    protected static function getFdKey($uid)
    {
        return RUN_UNIQID . 'fd' . $uid;
    }

    /**
     * 设置 链接的绑定类型
     *
     * @param int $fd
     * @param int $uid
     * @param int $type
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function setBindUidType(int $fd, int $uid, int $type = 0, $resetOther = true)
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        /**
         * @var \Hyperf\WebSocketServer\Sender $sender
         */
        $sender = $container->get(\Hyperf\WebSocketServer\Sender::class);

        $key      = self::getUidKey($uid);
        $cache    = self::getDriver();
        $fds      = $cache->get($key, []);
        $fds[$fd] = $type;
        if ($resetOther) {
            foreach ($fds as $ff => $tt) {
                if (is_numeric($ff) && $ff != $fd) {
                    $fds[$ff] = 0;
                }
            }
        }

        $cache->set($key, $fds);

    }

    protected static function getUidKey($uid)
    {
        return RUN_UNIQID . 'user' . $uid;
    }

    /**
     * 解绑一个 fd 到 uid
     *
     * @param int $fd
     * @param int $uid
     */
    public static function unBindUid(int $fd)
    {
        $cache = self::getDriver();
        $key2  = self::getFdKey($fd);
        $uid   = $cache->get($key2);

        $key = self::getUidKey($uid);

        $fds = $cache->get($key, []);
        unset($fds[$fd]);
        $fds['fds'] = array_diff($fds['fds'], [ $fd ]);
        $cache->set($key, $fds);
        $cache->delete($key2);

    }

    /**
     * 根据uid 获取其关联的 某个类型的 fd
     *
     * @param $uid
     * @param int $type
     */
    public static function getFd4Uid($uid, $type = 1): int
    {
        $key   = self::getUidKey($uid);
        $cache = self::getDriver();
        $fds   = $cache->get($key, []);
        if (empty($fds)) {
            return 0;
        }
        foreach ($fds as $fd => $type2) {
            if ($type2 == $type && is_numeric($fd)) {
                return $fd;
            }
        }
        if (!empty($fds['fds'])) {
            return (int)$fds['fds'][0];
        }

        return 0;
    }


    /**
     * 根据uid获取其关联的fd列表
     *
     * @param $uid
     * @param int $type
     */
    public static function getFdS4Uid($uid): array
    {
        $key   = self::getUidKey($uid);
        $cache = self::getDriver();
        $fds   = $cache->get($key, []);

        return $fds;
    }


    /**
     * 根据uid获取其关联的fd列表
     *
     * @param $uid
     * @param int $type
     */
    public static function getUid4Fd($fd): int
    {
        $key   = self::getFdKey($fd);
        $cache = self::getDriver();
        $uid   = $cache->get($key, 0);

        return $uid;
    }

    /**
     * @return \Hyperf\Cache\Driver\DriverInterface
     */
    public static function getDriver()
    {
        if (self::$cacheDriver) {
            return self::$cacheDriver;
        }
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        /**
         * @var CacheManager $service
         */
        $service = $container->get(CacheManager::class);

        self::$cacheDriver = $service->getDriver('uinbind');

        return self::$cacheDriver;
    }

}