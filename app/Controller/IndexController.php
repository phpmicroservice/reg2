<?php

namespace App\Controller;

use App\Core\Cache;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class IndexController extends AbstractController
{

    public function index()
    {
        $user   = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method'  => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function downexecl(ResponseInterface $response): Psr7ResponseInterface
    {
        $key  = $this->request->input('key');
        $data = Cache::get($key);
        $file = $data['file'];
        if(is_file($file) && file_exists($file)){
            return $response->download($file, basename($file));
        }

        return $data;
    }

    public function downexecl2(ResponseInterface $response): Psr7ResponseInterface
    {

        return $response->download(BASE_PATH . '/public/file.csv', 'filename.csv');
    }

}
