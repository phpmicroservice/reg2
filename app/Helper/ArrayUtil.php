<?php


namespace App\Helper;


/**
 * Class ArrayUtil
 * 数组助手
 *
 * @package App\Helper
 */
class ArrayUtil
{

    /**
     * 是关联数组 转 索引数组
     * @param array $array
     * @return array
     */
    static public function kv2indexArray($array)
    {
        $re = [];
        foreach ($array as $value) {
            $re[] = $value;
        }

        return $re;
    }
    /**
     * 返回数组中指定多列
     *
     * @param $input
     * @param null $column_keys 要取出的列名，逗号分隔，如不传则返回所有列
     * @param null $index_key 作为返回数组的索引的列
     * @return array
     */
    static public function array_columns($input, $column_keys = null, $index_key = null)
    {
        $result = array();
        $keys   = isset($column_keys) ? explode(',', $column_keys) : array();
        if ($input) {
            foreach ($input as $k => $v) {
                // 指定返回列
                if ($keys) {
                    $tmp = array();
                    foreach ($keys as $key) {
                        $tmp[$key] = $v[$key]??null;
                    }
                } else {
                    $tmp = $v;
                }
                // 指定索引列
                if (isset($index_key)) {
                    $result[$v[$index_key]] = $tmp;
                } else {
                    $result[] = $tmp;
                }

            }
        }

        return $result;
    }


    /**
     * 数组的多字段排序
     *
     * @param $array
     * @return mixed|null
     */
    static public function sortArrByManyField($array)
    {
        $args = func_get_args(); // 获取函数的参数的数组
        if (empty($args)) {
            return null;
        }
        $arr = array_shift($args);
        if (!is_array($arr)) {
            throw new \Exception("第一个参数不为数组");
        }
        foreach ($args as $key => $field) {
            if (is_string($field)) {
                $temp = array();
                foreach ($arr as $index => $val) {
                    $temp[$index] = $val[$field];
                }
                $args[$key] = $temp;
            }
        }
        $args[] = &$arr;//引用值
        call_user_func_array('array_multisort', $args);

        return array_pop($args);
    }

    /**
     * array_merge 升级版本，数字索引 的 数组也会覆盖
     *
     * @return false|mixed
     */
    static public function array_mergeS()
    {
        $val2 = array();
        foreach (func_get_args() as $k => $va) {
            foreach ($va as $k2 => $value) {
                if (is_array($value)) {
                    $val2[$k2][] = $value;
                } else {
                    $val2[$k2] = $value;
                }
            }
        }

        foreach ($val2 as $k3 => $value3) {
            if (is_array($value3)) {
                $val2[$k3] = call_user_func_array('\App\Helper\ArrayUtil::array_mergeS', $value3);
            } else {
                $val2[$k3] = $value3;
            }
        }

        return $val2;
    }


    /**
     * 转置数组
     *
     * @param $input
     * @return array
     */
    static public function transpose($input)
    {
        $arr1  = [];
        $keys  = [];
        $first = array_shift($input);
        array_unshift($input, $first);
        foreach ($first as $k => $value) {
            $keys[] = $k;
            $arr1[] = [ $k ];
            //确定转置后的数组有几行
        }

        foreach ($input as $k => $value) {
            foreach ($keys as $k1 => $v1) {
                $arr1[$k1][] = $value[$v1] ?? null;
            }
        }

        return $arr1;
    }


    /**
     *
     * @param array $p
     * @param callable $callback
     */
    static public function splicArray(array $p, callable $callback, $limit = 1000)
    {
        $uins   = $p[0];
        $reList = [];
        if (count($uins) > $limit) {
            $jixu = true;
            while ($jixu) {
                $uinArr = array_splice($uins, 0, $limit);
                $p[0]   = $uinArr;
                $list   = call_user_func_array($callback, $p);
                $reList = array_merge($reList, $list);
                $jixu   = !empty($uins);
            }

        } else {
            $reList = call_user_func_array($callback, $p);
        }

        return $reList;

    }

    /**
     * 增加引用传值
     *
     * @param array $p
     * @param callable $callback
     * @param int $limit
     */
    static public function splicArray2(array $p, callable $callback, $limit = 1000)
    {
        $listRe = [];
        array_unshift($p, $listRe);
        $uins = $p[1];

        if (count($uins) > $limit) {
            $jixu = true;
            while ($jixu) {
                $uinArr = array_splice($uins, 0, $limit);
                $p[1]   = $uinArr;
                $p[0]   = $listRe;
                $listRe = call_user_func_array($callback, $p);
                $jixu   = !empty($uins);
            }

        } else {
            $listRe = call_user_func_array($callback, $p);
        }

        return $listRe;

    }


}