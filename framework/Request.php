<?php
declare(strict_types=1);

namespace Ltphp;

use Exception;

class Request
{
    private static array $data = [];
    
    public function __construct()
    {
        // todo 自动判断提交方法并初始化数据
    }
    
    public static function set($method, $key, $value): void
    {
        $method = strtoupper($method);
        self::$data[$method][$key] = $value;
    }
    
    public static function setParams($method, array $params): void
    {
        $method = strtoupper($method);
        foreach ($params as $key => $value) {
            self::$data[$method][$key] = $value;
        }
    }
    
    public function get(string $key)
    {
        // return self::$data[$method][$key] ?? null;
    }
    
    public static function all(): array
    {
        return self::$data;
    }
    
    
}