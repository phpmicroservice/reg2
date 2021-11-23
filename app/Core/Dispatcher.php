<?php

namespace App\Core;

/**
 * Description of dispatch
 *
 * @author zhenyou
 */
class Dispatcher
{

    private $router;
    public $Frame;
    public $server;
    private $data;

    /**
     * 构造函数
     * @param Frame $Frame
     * @param \Swoole\WebSocket\Server $server
     */
    public function __construct(\Swoole\WebSocket\Frame $Frame, \Swoole\WebSocket\Server $server)
    {
        $this->Frame  = $Frame;
        $this->server = $server;
        $data         = \json_decode($Frame->data, true);
        $this->router = new Router($data);
        $this->data   = new Data($this, $data);
    }

    /**
     * 调度分发
     * @return string
     */
    public function dispatch()
    {

        if (json_last_error() != JSON_ERROR_NONE || empty($this->data)) {
            // 数据解析失败,空数据
            return $this->data->assembly('ws', 'sys', [
                        '数据处理错误，空数据或解码失败'
            ]);
        }

        $HandlerClass = $this->router->getController();
        $ActionName   = $this->router->getAction();
        echo " HandlerClass :$HandlerClass ;ActionName:$ActionName  \n";
        if (!class_exists($HandlerClass)) {
            return $this->data->assembly('ws', 'sys', [
                        '没找到相关的处理器'
            ]);
        }

        if (!method_exists($HandlerClass, $ActionName)) {
            return $this->data->assembly('ws', 'sys', [
                        '没找到相关的处理方法'
            ]);
        }

        $Handler = new $HandlerClass($this);
        try {
            $alc = VaLogin::validateLogin($this->getFd(),$HandlerClass,$ActionName);
            if(!$alc){
                throw new \Exception("没有登录！");
            }
            $res = call_user_func([$Handler, $ActionName]);
            if (!is_string($res) && !is_null($res)) {
                throw new \Exception("控制器返回信息错误！");
            }
        } catch (\Exception $ex) {
            $res = $this->data->assembly('ws', 'syserror', [
                '控制器处理出错', $ex->getMessage(), $ex->getTrace()
            ],1);
        }catch (\Error $error){
            $res = $this->data->assembly('ws', 'syserror', [
                '控制器处理出错', $error->getMessage(), $error->getTrace()
            ],1);
        }

        return $res;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFd()
    {
        return $this->Frame->fd;
    }

    /**
     * 发送数据给目标链接
     * @param int $to
     * @param string $data
     */
    public function send(int $to = null, string $data)
    {
        if ($to === null) {
            $to = $this->Frame->fd;
        }
        $this->server->push($to, $data);
    }

}
