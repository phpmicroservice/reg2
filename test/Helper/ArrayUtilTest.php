<?php


namespace HyperfTest\Helper;


use App\Helper\ArrayUtil;
use HyperfTest\CliCase;

/**
 * Class ArrayUtilTest
 *
 * @package HyperfTest\Helper
 * @extends composer test2 test/Helper/ArrayUtilTest.php
 * @group ready
 */
class ArrayUtilTest extends CliCase
{


    public function testA()
    {
        $arr  = [ 1, 2 ];
        $arr2 = [ 1, 0, 5 ];
        $this->assertEquals([ 1, 0, 5 ], ArrayUtil::array_mergeS($arr, $arr2));
    }

    public function testB()
    {
        $arr  = [
            2 => [
                1 => 'a',
                3 => 'c'
            ]
        ];
        $arr2 = [ 1, 0, 5 ];
        $this->assertEquals([ 1, 0, 5 ], ArrayUtil::array_mergeS($arr, $arr2));
    }

    /**
     * 转置 函数的测试
     */
    public function testTranspose()
    {

        //定义一个二维数组
        $arr = array(
            array( 1, 2, 3, ),
            array( 4, 5, 6 )
        );


        $this->assertEquals([
                                [ 0, 1, 4 ],
                                [ 1, 2, 5 ],
                                [ 2, 3, 6 ]
                            ], ArrayUtil::transpose($arr));


    }

    /**
     * 详细的转置测试
     */
    public function testTranspose2()
    {

        //定义一个二维数组
        $arr = array(
            array( 'uin' => 1, 'a' => '1a' ),
            array( 'uin' => 2, 'a' => '2a' )
        );


        $this->assertEquals([
                                [
                                    0 => "uin",
                                    1 => 1,
                                    2 => 2,
                                ],
                                [
                                    0 => "a",
                                    1 => "1a",
                                    2 => "2a"
                                ]
                            ], ArrayUtil::transpose($arr));


    }

    /**
     * 测试 分批执行
     */
    public function testSplice()
    {
        $list   = range(1, 100);
        $listre = ArrayUtil::splicArray([ $list, 5 ], [ $this, 'call100' ], 10);
//        dump($listre);
        $this->assertEquals(range(5, 500, 5), $listre);
    }

    /**
     * 测试另一种分批执行
     */
    public function testSplice2()
    {
        $list   = range(1, 80);
        $listre = ArrayUtil::splicArray2([ $list, 5 ], [ $this, 'call200' ], 10);
        $this->assertEquals(range(5, 400, 5), $listre['list']);
    }

    public function call200($re, $list, $x)
    {
        if (!($re['time'] ?? 0)) {
            sleep(1);
            $re['time'] = time();
            $re['list'] =[];
        }
        foreach ($list as $value) {
            $re['list'][] = $value * $x;
        }
        return $re;

    }


    public function call100($list, $x)
    {
        $list2 = [];
        foreach ($list as $value) {
            $list2[] = $value * $x;
        }

        return $list2;

    }


}