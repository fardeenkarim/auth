<?php
namespace App\Core;

class App {
    public static function init() {
        // Register Autoloader
        spl_autoload_register(function ($class) {
            $prefix = 'App\\';
            $base_dir = __DIR__ . '/../';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });

        // Load Config
        require_once __DIR__ . '/../Config/config.php';
        
        // Start Session (Secure)
        Session::start();

        // Check for Remember Me Cookie if not logged in
        if (empty($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $userModel = new \App\Models\User();
            $user = $userModel->findByRememberToken($token);
            
            if ($user) {
                // Log in logic (simplified)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['is_logged_in'] = true;
                // Note: If 2FA is required, remember me usually bypasses it for "trusted devices" 
                // OR we should require it. For this implementation, we bypass (Login Persistence).
            }
        }
    }

    public static function run() {
        // Initialize Router
        Router::resolve();
    }
}
