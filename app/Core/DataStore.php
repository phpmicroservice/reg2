<?php


namespace App\Core;


/**
 * Class DataStore
 * 数据储存对象
 *
 * @package App\Core
 */
class DataStore
{

    private $data;
    private $dataString;
    private $type;
    private $store;
    private $limit = 102400;


    /**
     * 设置数据
     *
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 唤起，反序列化
     */
    public function __wakeup()
    {
        if ($this->type == 'cache') {
            $dataString = CacheData::get($this->store);

            $this->dataString = $dataString;
        } else {
            $this->dataString = $this->store;
        }
        $this->data = unserialize($this->dataString);
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * 睡眠，序列化
     *
     * @return string[]
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __sleep()
    {
        $this->getDataString();
        if (strlen($this->dataString) > $this->limit) {
            $this->type  = 'cache';
            $this->store = md5($this->dataString);
            CacheData::set($this->store, $this->dataString);
        } else {
            $this->type  = 'original';
            $this->store = $this->dataString;
        }

        return [ 'type', 'store' ];
    }

    private function getDataString()
    {
        if (empty($this->dataString)) {
            $this->dataString = serialize($this->data);
        }

        return $this->dataString;;
    }

    public function toArray()
    {

    }


}