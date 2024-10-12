<?php
declare(strict_types=1);

namespace Ltphp;
use Exception;

class Router
{
    /**
     * @throws Exception
     */
    public function route(): void
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUri = ltrim($requestUri, '/');
        var_dump($requestUri);
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        
        // $basePath = '/your-app-base-path';
        // $requestUri = ltrim(str_replace($basePath, '', $requestUri), '/');
        
        $segments = explode('/', $requestUri);
        var_dump($segments);
        $defaultModule = \Ltphp\Config::get('app.default_app');
        $defaultController = 'Index';
        $defaultAction = 'index';
        
        $module = $segments[0] ?? $defaultModule;
        $controller = $segments[1] ?? $defaultController;
        $action = $segments[2] ?? $defaultAction;
        
        // 参数处理
        $params = [];
        for ($i = 3; $i < count($segments); $i += 2) {
            if (isset($segments[$i + 1])) {
                $params[$segments[$i]] = $segments[$i + 1];
            } else {
                // 如果没有对应的值，则可以认为这是最后一个参数，或者报错
                $params[$segments[$i]] = null;
            }
        }
        
        /*$validControllers = ['index', 'user', 'post']; // 示例白名单
        if (!in_array($controller, $validControllers)) {
            throw new Exception('Invalid controller');
        }
        
        $validActions = ['index', 'view', 'edit']; // 示例白名单
        if (!in_array($action, $validActions)) {
            throw new Exception('Invalid action');
        }*/
        
        
        $controllerClass = "\\app\\{$module}\\controller\\{$controller}";
        echo($controllerClass);
        
        if (!class_exists($controllerClass)) {
            throw new Exception('Controller not found');
        }
        
        $controllerInstance = new $controllerClass();
        if (!method_exists($controllerInstance, $action)) {
            throw new Exception('Action not found');
        }
        
        call_user_func_array([$controllerInstance, $action], $params);
        
        
        // $controller_url = APP_CONTROLLER_PATH . "/{$this->group_name}/{$this->module_name}Controller.php";
        // if (!file_exists($controller_url)) {
        //     sysError('访问地址不存在！');
        // }
        // gf_require($controller_url);
        // $m = $this->module_name . 'Controller';
        // $a = $this->action_name;
        //
        // $Controller = new $m;
        // return $Controller->$a();
    }
}