<?php


namespace App\Helper;


use App\Core\Cache;

class FileDown
{

    /**
     * @param $content
     * @param int $limit
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    static public function downFile($content, $limit = 1000)
    {
        $file = $content['file'];
        $size = $content['size'];
        if ($size < $limit) {
            return [
                'type'   => 'base64',
                'base64' => base64_encode(file_get_contents($file))
            ];
        }
        $key  = md5(serialize([ $file, $size ]));
        $data = [
            'file' => $file,
            'time' => time()
        ];
        Cache::set($key, $data, 3600 * 24);

        return [
            'type' => 'http',
            'key'  => $key
        ];

    }

}