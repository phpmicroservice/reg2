<?php

namespace App\Core;

/**
 * Description of Data
 * s 模块 r 路由 （控制器_方法） p 客户端追踪标志 u服务器端标志
 *
 * @author dongasai
 */
class Data
{

    private $Dispatcher;
    private $request;
    private $uniqid;

    /**
     * 构造函数
     *
     * @param Dispatcher $Dispatcher
     * @param type $request
     */
    public function __construct(Dispatcher $Dispatcher, $request)
    {
        $this->Dispatcher = $Dispatcher;
        $this->request    = $request;
        $this->uniqid     = uniqid();
    }

    /**
     * 组装数据
     *
     * @param $server
     * @param $router
     * @param $data
     * @return string
     */
    public function assembly($server, $router, $data, $error = 0)
    {
        $p = $this->request['p'];

        return self::assemblyStatic($error, $server, $router, $data, $this->request['p'], $this->uniqid);
    }

    /**
     * 组装数据
     *
     * @param $error
     * @param $server
     * @param $router
     * @param $data
     * @param $p
     * @param null $uniqid
     * @return false|string
     *
     */
    static public function assemblyStatic($error, $server, $router, $data = [], $p = null, $uniqid = '')
    {
        if (empty($p)) {
            $p = uniqid();
            $uniqid = uniqid();
        }

        return json_encode([
                               'e' => $error,
                               's' => $server,
                               'r' => $router,
                               'd' => $data,
                               'p' => $p,
                               'u' => $uniqid
                           ]);
    }

    /**
     *
     * @param $server
     * @param $router
     * @param $data
     * @return false|string
     */
    public static function assemblyS($server, $router, $data)
    {
        return json_encode([
                               's' => $server,
                               'r' => $router,
                               'd' => $data
                           ]);
    }

    /**
     * 组装数据
     *
     * @param array $data
     * @param string $router
     * @param string $server
     * @return string
     */
    public function assembly2(array $data = [], string $router = null, string $server = null)
    {
        $router = $router ?? $this->request['r'];
        $server = $server ?? $this->request['s'];

        return $this->assembly($server, $router, $data);
    }

    public function getData($index = null)
    {
        if ($index) {
            return $this->request['d'][$index] ?? null;
        }

        return $this->request['d'];
    }

    /**
     * 获取数据，有默认值的
     *
     * @param $index
     * @param null $Default
     * @return mixed|null
     */
    public function getDataDefault($index, $Default = null)
    {
        return $this->request['d'][$index] ?? $Default;
    }

    /**
     * 魔法函数
     *
     * @param string $name
     */
    public function __get($name)
    {
        $this->request['d'][$name] ?? null;
    }

}
