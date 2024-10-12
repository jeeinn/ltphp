<?php

declare(strict_types=1);

namespace Ltphp;

use Exception;

class Config
{
    private static array $config = [];
    
    public static function get(string $key)
    {
        return self::$config[$key] ?? null;
    }
    
    public static function getAll(): array
    {
        return self::$config;
    }
    
    public static function set(string $key, $value)
    {
        self::$config[$key] = $value;
        return $value;
    }
    
    /**
     * 写入配置，如 app.default_app
     * @throws Exception
     */
    public static function load(): void
    {
        // config
        $configs = [];
        $configPath = ROOT_PATH . 'config' . DIRECTORY_SEPARATOR;
        // 仅根目录配置文件
        $fh = opendir($configPath);
        if ($fh) {
            while (($file = readdir($fh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $filename = basename($file, '.php');
                    $configs = require_once $configPath . $file;
                    // step2 合并配置
                    foreach ($configs as $key => $value) {
                        $configKey = strtolower($filename) . '.' . strtolower($key);
                        Config::set($configKey, $value);
                    }
                }
            }
            closedir($fh);
        } else {
            $msg = "Config file ({$configPath}) open failed or not found.";
            exit(lt_msg($msg));
        }
        // var_dump(\Ltphp\Config::getAll());
    }
}