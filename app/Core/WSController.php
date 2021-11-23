<?php

namespace App\Core;

use App\Queue\Task;
use App\Service\QueueService;

/**
 * Description of WSController
 *
 * @author zhenyou
 * @property-read Dispatcher $dispatcher
 * @property-read Data $data
 * ws 链接控制器 基类
 */
class WSController
{

    public $dispatcher;
    public $data;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->data       = $this->dispatcher->getData();
    }

    /**
     * 组装数据
     *
     * @param bool $result
     * @param string $message
     * @param array $data
     * @return string
     */
    protected function assembly(bool $result = true, string $message = '', $data = [])
    {
        $res = [
            "result"  => $result,
            'message' => $message,
            'data'    => $data
        ];

        return $this->data->assembly2($res);
    }

    /**
     * 获取数据
     *
     * @param $index
     * @param null $de
     * @return mixed|null
     */
    protected function getData($index, $de = null)
    {
        $value = $this->data->getData($index);

        return $value ?? $de;
    }


    /**
     * 验证失败的返回
     *
     * @param Validation $validation
     * @param string $message
     * @return string
     */
    protected function assemblyValidation(Validation $validation, string $message = "数据验证失败")
    {
        $data = [
            "result"  => false,
            'message' => [ $message, $validation->firstError() ],
            'params'  => $validation->all()
        ];

        return $this->data->assembly2($data);

    }

    /**
     * 异步队列任务
     *
     * @param $name
     * @param $params
     */
    protected function queueAsync($name, $params)
    {
        $container = \Hyperf\Utils\ApplicationContext::getContainer();
        $service   = $container->get(QueueService::class);

        $service->pushJob($name, $params);
    }


}
