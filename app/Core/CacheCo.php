<?php


namespace App\Core;


use Hyperf\Cache\CacheManager;

class CacheCo
{

    /**
     * @var \Hyperf\Cache\Driver\DriverInterface $cacheDriver
     */
    public static $cacheDriver;

    /**
     * 获取数据
     *
     * @param $key
     * @param null $default
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function get($key, $default = null)
    {
        if (!is_string($key)) {
            $key = md5(serialize($key));
        }
        $cache = self::getDriver();

        return $cache->get($key, $default);
    }

    /**
     * 设置数据
     *
     * @param $key
     * @param $value
     * @param null $ttl
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function set($key, $value, $ttl = null)
    {
        if (!is_string($key)) {
            $key = md5(serialize($key));
        }
        $cache = self::getDriver();

        return $cache->set($key, $value, $ttl);
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

        self::$cacheDriver = $service->getDriver('co');

        return self::$cacheDriver;
    }

    /**
     * 缓存数据
     *
     * @param $name2
     * @param $expire
     * @param $callback
     * @param array $param_arr
     * @param false $re
     * @return false|mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    static public function call_cache($name2, $expire, $callback, $param_arr = array(), $re = false)
    {
        $name = md5(serialize($name2));
        $old  = self::get($name);
        if ($re || !$old) {
            $data = call_user_func_array($callback, $param_arr);
            self::set($name, $data, $expire);

            return $data;
        } else {
            return self::get($name);
        }
    }

}