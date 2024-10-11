<?php
declare(strict_types=1);

namespace Ltphp;
error_reporting(E_ALL);
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Dotenv\Dotenv;

class Boot
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
        
    }
    
    protected static function loadEnv(): void
    {
        // .env
        $dotenv = Dotenv::createUnsafeMutable(ROOT_PATH);
        $dotenv->load();
        
        // special env
        $env = getenv('APP_ENV') ?? 'production';
        
        if (file_exists(ROOT_PATH . ".env.{$env}")) {
            $dotenv = Dotenv::createUnsafeMutable(ROOT_PATH, ".env.{$env}");
            $dotenv->load();
        }
    }
    
    // 加载配置文件
    protected static function loadConfig(): void
    {
        // config
        require_once ROOT_PATH . 'common' . DIRECTORY_SEPARATOR . 'config.php';
    }
    
    protected static function loadFunctions(): void
    {
        require_once FRAMEWORK_PATH . 'common' . DIRECTORY_SEPARATOR . 'function.php';
        $rootFunctionPath = ROOT_PATH . 'common' . DIRECTORY_SEPARATOR . 'function.php';
        if (is_file($rootFunctionPath)) {
            require_once $rootFunctionPath;
        }
    }
    
    public static function run(): void
    {
        try {
            self::defineSysPath();
            self::loadEnv();
            self::loadConfig();
            self::loadFunctions();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        
    }
}