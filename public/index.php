<?php
require_once __DIR__ . '/../app/Core/App.php';

use App\Core\App;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

// Initialize App (Autoloader, Config, Session)
// Initialize App (Autoloader, Config, Session)
App::init();

// Security Headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
// header("Content-Security-Policy: default-src 'self' https: 'unsafe-inline'"); // Commented to avoid breaking CDNs/inline scripts without strict testing

try {
    // Define Routes
    Router::get('/', [AuthController::class, 'showLogin']);
    Router::get('/login', [AuthController::class, 'showLogin']);
    Router::post('/api/login', [AuthController::class, 'apiLogin']);
    Router::get('/logout', [AuthController::class, 'logout']);

    // Forgot / Reset Password
    Router::get('/forgot-password', [AuthController::class, 'showForgotPassword']);
    Router::post('/api/forgot-password', [AuthController::class, 'handleForgotPassword']);
    Router::get('/reset-password', [AuthController::class, 'showResetPassword']);
    Router::post('/api/reset-password', [AuthController::class, 'handleResetPassword']);

    // Registration
    Router::get('/register', [AuthController::class, 'showRegister']);
    Router::post('/api/register', [AuthController::class, 'apiRegister']);
    Router::post('/api/verify-email-otp', [AuthController::class, 'apiVerifyEmailOtp']);
    Router::post('/api/resend-otp', [AuthController::class, 'apiResendOtp']);

    // Dashboard
    Router::get('/dashboard', [DashboardController::class, 'index']);
    


    // Run App (Dispatch Route)
    App::run();
    
    // NOTE: App::run() doesn't handle 404 natively if it's just a dispatcher helper? 
    // Let's check Router::dispatch logic or if App::run calls Router. 
    // Assuming Router matches and executes. If no match found? 
    // We should probably check if Router handled it. 
    // If Router uses a static array and direct execution, we might need a fallback here.
    // For this codebase, I'll add the 404 fallback at the end if App::run doesn't exit.

} catch (Throwable $e) {
    // Log to file
    $logMsg = date('[Y-m-d H:i:s] ') . "Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n";
    file_put_contents(__DIR__ . '/../debug_error.log', $logMsg, FILE_APPEND);
    
    // Return JSON if API request
    $isApi = strpos($_SERVER['REQUEST_URI'], '/api') !== false;
    
    if ($isApi) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Critical System Error: ' . $e->getMessage()]);
    } else {
        echo "<h1>Critical Error</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
