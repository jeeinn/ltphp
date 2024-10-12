<?php
declare(strict_types=1);

namespace Ltphp;
class DefineConstant
{
    protected static function defineSysPath(): void
    {
        $basePath = realpath(dirname(__DIR__));
        
        // 根目录
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', $basePath . DIRECTORY_SEPARATOR);
        }
        // 应用目录
        if (!defined('APP_PATH')) {
            define('APP_PATH', $basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
        }
        // 框架目录
        if (!defined('FRAMEWORK_PATH')) {
            define('FRAMEWORK_PATH', $basePath . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR);
        }
        // 运行时目录
        if (!defined('RUNTIME_PATH')) {
            define('RUNTIME_PATH', $basePath . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR);
        }
        
        // 外网公共目录
        if (!defined('PUBLIC_PATH')) {
            define('PUBLIC_PATH', $basePath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
        }
    }
    
    protected static function defineSysConstant(): void
    {
        // 是否 cli 模式
        if (!defined('IS_CLI')) {
            define('IS_CLI', php_sapi_name() === 'cli');
        }
        // 是否POST
        if (!defined('IS_POST')) {
            define('IS_POST', php_sapi_name() === 'cli');
        }
    }
    
    public static function define(): void
    {
        self::defineSysConstant();
        self::defineSysPath();
    }
}