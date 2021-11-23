<?php


namespace App\Core;


use Hyperf\Cache\CacheManager;

class Cache
{

    /**
     * @var \Hyperf\Cache\Driver\DriverInterface $cacheDriver
     */
    public static $cacheDriver;

    /**
     * 获取数据
     *
     * @param $key
     * @param mixed $default
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function get($key, $default = null)
    {
        if (!is_string($key)) {
            $key = md5(serialize($key));
        }
        $cache = self::getDriver();
        if ($cache->has($key)) {
            return $cache->get($key);
        }
        return $default;
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
     * 追加列表
     *
     * @param $key
     * @param $value
     * @param null $ttl
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    static public function addList($key, $value, $max = 20, $ttl = null)
    {
        $list = self::get($key);
        $list[] = $value;
        if (count($list) > $max) {
            $list = array_slice($list, -$max, 20);
        }
        return self::set($key, $list, $ttl);
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

        self::$cacheDriver = $service->getDriver('default');

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
        $old = self::get($name);
        if ($re || !$old) {
            $data = call_user_func_array($callback, $param_arr);
            self::set($name, $data, $expire);

            return $data;
        } else {
            return self::get($name);
        }
    }

    /**
     * 缓存数据，使用版本作为缓存标志
     *
     * @param $name2
     * @param $expire
     * @param $callback
     * @param array $param_arr
     * @param false $re
     */
    static public function call_cache_version($name2, $expire, $callback, $param_arr = array(), $re = false)
    {
        $name = md5(serialize($name2) . APP_VERSION);
        $old = self::get($name);
        if ($re || !$old) {
            $data = call_user_func_array($callback, $param_arr);
            self::set($name, $data, $expire);

            return $data;
        } else {
            $data = self::get($name);
            $data['name2'] = serialize($name2);
            $data['cache'] = true;

            return $data;
        }
    }


}