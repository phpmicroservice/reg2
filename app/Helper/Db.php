<?php

namespace App\Helper;

use App\Core\CacheCo;

class Db
{

    static $tableExits;

    /**
     * 表是否存在
     *
     * @param $index
     * @param $tableName
     * @return false|mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    static public function tableExits($index, $tableName)
    {
        return CacheCo::call_cache([ $index, $tableName, __FILE__, __FUNCTION__ ], 3600, function ($index, $tableName) {
            $sql = "show tables like '$tableName';";
            $q   = \Hyperf\DbConnection\Db::connection($index)->select($sql);
            if (empty($q)) {
                return false;
            }

            return true;
        },                         [ $index, $tableName ]);

    }

}