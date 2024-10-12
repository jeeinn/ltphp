<?php
declare(strict_types=1);

namespace Ltphp;

class Tracer
{
    private const FRAMEWORK_TRACER_INFO = 'framework_tracer_info';
    
    public static function start(): void
    {
        $info = [
            'startTime' => microtime(true),
            'memoryUsage' => memory_get_usage(),
        ];
        // 保存在系统配置中
        Config::set(self::FRAMEWORK_TRACER_INFO, $info);
        
        // 仅记录但并不写入(解决依赖路径常量时的时间计算)
        /*$msg = '[' . date('Y-m-d H:i:s') . '][LTPHP][Tracer] ';
        $msg .= json_encode($info, JSON_UNESCAPED_UNICODE);
        $msg .= PHP_EOL;
        (new Logger())->write($msg, 'trace');
        return $msg;*/
    }
    
    public static function end(): string
    {
        $isDebug = env('APP_DEBUG', Config::get('app.debug'));
        if (!$isDebug) return '';
        
        $startTraceInfo = Config::get(self::FRAMEWORK_TRACER_INFO);
        $endTime = microtime(true);
        $memoryUsage = memory_get_usage();
        
        $timeUsed = $endTime - $startTraceInfo['startTime'];
        if ($timeUsed > 1) {
            $timeUsed .= ' s';
        } else {
            $timeUsed = ($timeUsed * 1000) . ' ms';
        }
        
        $info = [
            'timeUsed' => $timeUsed,
            'memoryIncreased' => ($memoryUsage - $startTraceInfo['memoryUsage']) / 1024 / 1024 . ' MB',
            'startTime' => $startTraceInfo['startTime'],
            'endTime' => $endTime,
            'startMemoryUsage' => $startTraceInfo['memoryUsage'] / 1024 / 1024 . ' MB',
            'endMemoryUsage' => $memoryUsage / 1024 / 1024 . ' MB',
        ];
        
        $msg = '[' . date('Y-m-d H:i:s') . '][LTPHP][Tracer] ';
        $msg .= json_encode($info, JSON_UNESCAPED_UNICODE);
        $msg .= PHP_EOL;
        (new Logger())->write($msg, 'trace');
        return $msg;
    }
}