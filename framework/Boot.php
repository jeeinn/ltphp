<?php
declare(strict_types=1);

namespace Ltphp;
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Exception;

class Boot
{
    /**
     * @description 优先载入框架函数
     * @author 谢云伟 2024/10/12
     * @return void
     */
    protected static function loadFunctions(): void
    {
        require_once FRAMEWORK_PATH . 'functions.php';
        $rootFunctionPath = APP_PATH . 'functions.php';
        if (is_file($rootFunctionPath)) {
            require_once $rootFunctionPath;
        }
    }
    
    
    public static function start(): void
    {
        try {
            Tracer::start();
            DefineConstant::define();
            Env::load();
            self::loadFunctions();
            Config::load(); // 可以使用已定义的函数
            date_default_timezone_set(env('APP_TIMEZONE', Config::get('app.timezone')));
            (new Router)->route();
            Tracer::end();
            exit(0);
        } catch (Exception $e) {
            echo lt_msg($e->getMessage());
            echo lt_msg($e->getTraceAsString());
            Tracer::end();
        }
        
    }
}