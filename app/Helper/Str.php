<?php


namespace App\Helper;


class Str
{

    /**
     * 过滤特殊字符，只保留，中文，字母，数字
     * @param $chars
     * @param string $encoding
     * @return string
     */
    static public function match_chinese($chars, $encoding = 'utf8')
    {
        $pattern = ($encoding == 'utf8') ? '/[\x{4e00}-\x{9fa5}a-zA-Z0-9_]/u' : '/[\x80-\xFF]/';
        preg_match_all($pattern, $chars, $result);
        $temp = join('', $result[0]);

        return $temp;
    }

}