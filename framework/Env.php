<?php
declare(strict_types=1);

namespace Ltphp;
use Dotenv\Dotenv;

class Env
{
    public static function load(): void
    {
        // .env
        $dotenv = Dotenv::createMutable(ROOT_PATH);
        $dotenv->load();
        
        // special env
        $env = $_ENV['APP_ENV'] ?? 'production';
        if (file_exists(ROOT_PATH . ".env.{$env}")) {
            $dotenv = Dotenv::createMutable(ROOT_PATH, ".env.{$env}");
            $dotenv->load();
        }
    }
}