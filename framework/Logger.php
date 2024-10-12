<?php

namespace Ltphp;

use Exception;

class Logger
{
    private string $logFile = '';
    private string $traceFile = '';
    
    /**
     * @throws Exception
     */
    public function __construct()
    {
        $logPath = RUNTIME_PATH . 'log' . DIRECTORY_SEPARATOR;
        $logFile = $logPath . date('Ym_d') . '.log';
        
        $tracePath = RUNTIME_PATH . 'trace' . DIRECTORY_SEPARATOR;
        $traceFile = $tracePath . date('Ym_d') . '.log';
        // 尝试创建目录
        try {
            if (!is_dir($logPath) && !is_writable($logPath) && !mkdir($logPath, 0755, true)) {
                $msg = "Failed to create directory ({$logPath})";
                throw new Exception(lt_msg($msg));
            }
            if (!is_dir($tracePath) && !is_writable($tracePath) && !mkdir($tracePath, 0755, true)) {
                $msg = "Failed to create directory ({$tracePath})";
                throw new Exception(lt_msg($msg));
            }
        } catch (Exception $e) {
            $msg = "Error: " . $e->getMessage();
            throw new Exception(lt_msg($msg));
        }
        $this->logFile = $logFile;
        $this->traceFile = $traceFile;
    }
    
    /**
     * @description 立即写入日志
     * @author 谢云伟 2024/10/12
     * @param $msg
     * @param string $level
     * @param string $file
     * @return void
     */
    public function write($msg, string $level = 'debug', string $file = ''): void
    {
        if (empty($msg)) return;
        if (empty($file)) $file = $this->logFile;
        if ($level == 'trace') $file = $this->traceFile;
        file_put_contents($file, $msg, FILE_APPEND);
    }
}