<?php
require_once __DIR__ . '/../Core/Env.php';
use App\Core\Env;

// Load .env
Env::load(__DIR__ . '/../../.env');

// Database Configuration
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_DATABASE'));
define('DB_USER', getenv('DB_USERNAME'));
define('DB_PASS', getenv('DB_PASSWORD'));
define('DB_CHARSET', 'utf8mb4');

// App Configuration
define('APP_NAME', getenv('APP_NAME') ?: 'Auth');

// Base URL
if (getenv('APP_URL')) {
    define('BASE_URL', getenv('APP_URL'));
} else {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    $script = str_replace('/public', '', $script);
    define('BASE_URL', $protocol . "://" . $host . $script);
}

define('ROOT_PATH', dirname(__DIR__, 2));

// Email Configuration
define('MAIL_DRIVER', getenv('MAIL_DRIVER') ?: 'smtp');
define('MAIL_HOST', getenv('MAIL_HOST'));
define('MAIL_PORT', getenv('MAIL_PORT'));
define('MAIL_USERNAME', getenv('MAIL_USERNAME'));
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD'));
define('MAIL_ENCRYPTION', getenv('MAIL_ENCRYPTION'));
define('MAIL_FROM_ADDRESS', getenv('MAIL_FROM_ADDRESS'));
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: APP_NAME);

// Error Reporting
if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
