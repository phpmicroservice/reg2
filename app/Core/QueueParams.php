<?php

namespace App\Core;

use App\Inquiry\Helper\Validation\HelperValidation;
use App\Inquiry\InquiryBase;

/**
 * Description of QueueParams
 * 队列 参数 对象
 *
 * @author dongasai
 */
class QueueParams
{

    private $name;
    private $params;
    private $bid;

    public function __construct($name, $params)
    {
        $this->name   = $name;
        $this->params = $params;
    }

    public function setBid($bid)
    {
        $this->bid = $bid;
    }

    /**
     * 获取处理器
     *
     * @return InquiryBase
     * @throws \Exception
     */
    public function getHandle(): InquiryBase
    {
        $class = $this->getClass();

        return (new $class($this->bid, $this->params));
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getClass()
    {

        $list = \App\Inquiry\Lists::$list;
        $data = $list[$this->name] ?? '';
        if (!$data) {
            throw new \Exception("不存在的查询器！");
        }
        $class = $data['class'];

        return $class;
    }

    /**
     * 获取验证器
     *
     * @return \Inhere\Validate\Validation
     * @throws \Exception
     */
    public function getValidation(): \Inhere\Validate\Validation
    {
        $class = $this->getClass();
        if (!property_exists($class, 'validation')) {
            throw new \Exception("未定义验证器！ $class ");
        };
        $validation = $class::$validation;
        if (!class_exists($validation)) {
            throw new \Exception("定义验证器不存在: $validation ");
        }
        $va = new $validation($this->params);
        if (!($va instanceof \Inhere\Validate\Validation)) {
            throw new \Exception("验证器定义错误: $validation ");
        }
        if ($validation == HelperValidation::class) {
            $va->applyClass($class);
        }

        return $va;
    }

}
