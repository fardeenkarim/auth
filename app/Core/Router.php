<?php
namespace App\Core;

class Router {
    protected static $routes = [];

    public static function get($path, $callback) {
        self::$routes['GET'][$path] = $callback;
    }

    public static function post($path, $callback) {
        self::$routes['POST'][$path] = $callback;
    }

    public static function resolve() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        
        // Check if we are accessing via public/ (Ugly URL)
        if (strpos($path, $scriptDir) === 0) {
            $path = substr($path, strlen($scriptDir));
        } else {
            // We are accessing via Root (Clean URL) usually via .htaccess
            // scriptDir is /auth/public, we want /auth
            $projectRoot = dirname($scriptDir);
            
            // Handle edge case where project is at server root
            if ($projectRoot === '/' || $projectRoot === '\\') {
                 // Do nothing, path is already correct
            } elseif (strpos($path, $projectRoot) === 0) {
                 $path = substr($path, strlen($projectRoot));
            }
        }

        if ($path === '' || $path === '/') $path = '/';
        
        $callback = self::$routes[$method][$path] ?? false;

        if ($callback === false) {
             http_response_code(404);
             
             // Return JSON for API 404
             if (strpos($path, '/api') === 0 || strpos($path, '/dashboard') === 0) { // dashboard 404s also better as JSON if ajax, but mostly API
                 if (strpos($path, '/api') === 0) {
                     header('Content-Type: application/json');
                     echo json_encode(['status' => 'error', 'message' => '404 Not Found: ' . $path]);
                     return;
                 }
             }
             
             echo "404 Not Found";
             return;
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $action = $callback[1];
            call_user_func([$controller, $action]);
        } else {
            call_user_func($callback);
        }
    }
}
