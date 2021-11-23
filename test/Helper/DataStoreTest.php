<?php


namespace HyperfTest\Helper;


use App\Core\DataStore;
use HyperfTest\CliCase;

/**
 * Class DataStoreTest
 *
 * @package HyperfTest\Helper
 * @example composer test2 test/Helper/DataStoreTest.php
 */
class DataStoreTest extends CliCase
{

    public function testA()
    {
        $this->assert("sssss");
        $this->assert(range(1, 100, 2));
        $this->assert(range(1, 100000,));

    }


    private function assert($value)
    {
        $datas = new DataStore();
        $datas->setData($value);
        $serializeString = serialize($datas);
        dump($serializeString);
        $this->assertEquals($value, unserialize($serializeString)->getData());
    }

}