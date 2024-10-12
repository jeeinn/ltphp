<?php
/**
 * @description 环境变量
 * @author 谢云伟 2024/10/11
 * @param string|null $name
 * @param null $default
 * @return mixed|null
 */
function env(string $name = null, $default = null): mixed
{
    $value = null;
    if (!is_null($name)) {
        if (isset($_SERVER[$name])) $value = $_SERVER[$name];
        if (isset($_ENV[$name])) $value = $_ENV[$name];
        if ($value == 'true') return true;
        if ($value == 'false') return false;
    }
    return $default === $value ? $default : $value;
}

function config(): void
{
    echo 'call config function';
}

function lt_msg(string $message): string
{
    $bs = IS_CLI ? PHP_EOL : '<br/>';
    return '[' . date('Y-m-d H:i:s') . '][LTPHP] ' . $message . $bs;
}