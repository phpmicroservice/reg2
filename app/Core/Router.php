<?php

namespace App\Core;

/**
 * Description of Router
 *
 * @author dongasai
 * 路由
 */
class Router
{

    private $data;
    private $router;
    private $server;

    public function __construct($data)
    {
        $this->data   = $data;
        $this->router = explode('_', $data['r']);
        $this->server = $data['s'];
    }

    /**
     * 获取控制器
     */
    public function getController()
    {
        $c          = $this->router[0];
        $Controller = 'App\WSController\\' .$this->getModule(). '\\'.ucfirst($c) ;
        return $Controller;
    }

    /**
     * 获取模块
     */
    public function getModule()
    {
        $s = $this->server;
        return ucfirst($s);
    }

    /**
     * 获取处理方法
     */
    public function getAction()
    {
        return $this->router[1] ?? 'index';
    }

}
