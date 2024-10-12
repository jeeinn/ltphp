<?php
declare(strict_types=1);

namespace Ltphp;

use Exception;

class Router
{
    /**
     * @throws Exception
     */
    public function route(): mixed
    {
        // cli 模式下 将传入的第二个参数 解析为 PATH_INFO
        if (IS_CLI) $_SERVER['PATH_INFO'] = $_SERVER['argv'][1] ?? '';
        $requestUri = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '';
        if (empty($requestUri)) {
            throw new Exception('$_SERVER[\'PATH_INFO\'] is empty');
        }
        
        // 后续可支持二级目录
        /*$basePath = '/your-app-base-path';
        $requestUri = ltrim(str_replace($basePath, '', $requestUri), '/');*/
        
        // 去掉问号及之后的查询字符串部分
        $pathInfo = explode('?', $requestUri)[0];
        $segments = explode(DIRECTORY_SEPARATOR, trim($pathInfo, DIRECTORY_SEPARATOR));
        // 检查路径信息是否足够
        if (count($segments) < 2
            || (count($segments) == 2 && Config::get('app.default_app') === null)
        ) {
            throw new Exception('Invalid URL path or Config app.default_app is null.');
        }
        
        // 提取模块、控制器和方法
        $module = count($segments) == 2 ? Config::get('app.default_app') : array_shift($segments); // 模块
        $controller = ucfirst(array_shift($segments)); // 控制器
        $action = array_shift($segments); // 方法
        
        // 剩余部分是参数，直接放入 Request 类的 get 中
        while (!empty($segments)) {
            $key = array_shift($segments);
            $value = array_shift($segments) ?? null; // 使用 null 默认值防止数组越界
            $params[$key] = $value;
            Request::setParams('GET', $params);
        }
        
        // 示例白名单
        /*$validControllers = ['index', 'user', 'post'];
        if (!in_array($controller, $validControllers)) {
            throw new Exception('Invalid controller');
        }
        
        $validActions = ['index', 'view', 'edit'];
        if (!in_array($action, $validActions)) {
            throw new Exception('Invalid action');
        }*/
        
        // 自动加载
        $controllerFile = APP_PATH . $module . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $controller . '.php';
        if (!file_exists($controllerFile)) {
            throw new Exception(lt_msg("Controller file not found ({$controllerFile})"));
        }
        $moduleFunctionsFile = APP_PATH . $module . DIRECTORY_SEPARATOR . 'functions.php';
        if (file_exists($moduleFunctionsFile)) {
            require_once $moduleFunctionsFile;
        }
        require_once $controllerFile;
        
        $controllerClass = "\\app\\{$module}\\controller\\{$controller}";
        if (!class_exists($controllerClass)) {
            throw new Exception('Controller not found.');
        }
        $controllerInstance = new $controllerClass();
        if (!method_exists($controllerInstance, $action)) {
            throw new Exception('Action not found.');
        }
        
        // return call_user_func_array([$controllerInstance, $action], $params);
        return $controllerInstance->{$action}();
    }
}