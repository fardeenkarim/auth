<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\User;
use App\Core\SMTPMailer;
use App\Core\Security;
use App\Core\CSRF;
use App\Helpers\EmailTemplate;

class AuthController {
    
    public function showLogin() {
        // Render View
        require_once ROOT_PATH . '/resources/views/auth/login.php';
    }

    public function showRegister() {
        require_once ROOT_PATH . '/resources/views/auth/register.php';
    }

    public function apiLogin() {
        header('Content-Type: application/json');
        
        // Security Checks
        $this->verifyCsrf();
        $this->checkRateLimit();
        
        $this->handleLogin();
    }

    public function apiRegister() {
        header('Content-Type: application/json');
        $this->verifyCsrf();
        $this->handleRegister();
    }
    
    // Helper for checks
    private function verifyCsrf() {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
        if (!CSRF::check($token)) {
            echo json_encode(['status' => 'error', 'message' => 'Security Token Invalid (CSRF)']);
            exit;
        }
    }

    private function checkRateLimit() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!Security::checkRateLimit($ip)) {
            echo json_encode(['status' => 'error', 'message' => 'Too many attempts. Please try again in 15 minutes.']);
            exit;
        }
    }
    
    // Step 2: Verify Email OTP
    public function apiVerifyEmailOtp() {
        header('Content-Type: application/json');
        $this->verifyCsrf();
        $this->checkRateLimit(); // Also rate limit OTP guesses
        
        $userId = Session::get('user_id');
        $code = trim($_POST['code'] ?? '');
        
        if (!$userId || !Session::get('is_verification_pending')) {
             echo json_encode(['status' => 'error', 'message' => 'Session expired or invalid']);
             return;
        }

        if (empty($code)) {
             echo json_encode(['status' => 'error', 'message' => 'OTP Code required']);
             return;
        }

        $userModel = new User();
        $user = $userModel->verifyOtp($userId, $code);

        if ($user) {
            // Success
            $userModel->markAsVerified($userId);
            
            // Add to Trusted Devices (So next time they don't need OTP)
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            $ip = $_SERVER['REMOTE_ADDR'];
            if (!$userModel->isDeviceTrusted($userId, $userAgent)) {
                $userModel->addTrustedDevice($userId, $userAgent, $ip);
            }
            
            // Clear pending, set logged in
            Session::remove('is_verification_pending');
            Session::set('is_logged_in', true);
            Session::set('is_verified', true);
            Session::set('email', $user['email']);
            
            echo json_encode(['status' => 'success', 'redirect' => 'dashboard']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or expired OTP code']);
        }
    }

    public function apiResendOtp() {
        header('Content-Type: application/json');
        $this->verifyCsrf();
        $this->checkRateLimit();
        
        $userId = Session::get('user_id');
        $email = Session::get('email');
        
        if (!$userId || !Session::get('is_verification_pending')) {
             echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
             return;
        }
        
        $userModel = new User();
        
        // Generate new OTP
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $userModel->setOtp($userId, $code);
        
        // Send Email
        try {
            $mailer = new SMTPMailer();
            $html = EmailTemplate::generate("Verification Code", "Here is your new verification code. It is valid for 15 minutes.", $code);
            $mailer->send($email, "Resend Verify Account", $html);
            echo json_encode(['status' => 'success', 'message' => 'New code sent to your email.']);
        } catch (\Exception $e) {
            error_log("SMTP Error: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Please try again later.']);
        }
    }

    private function handleRegister() {
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'whatsapp_number' => trim($_POST['whatsapp_number'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'password' => $_POST['password'] ?? ''
        ];

        // Basic Validation
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['location']) || empty($data['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'All required fields must be filled']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email address format']);
            return;
        }
        
        // Password Complexity: At least 8 chars, 1 letter, 1 number
        if (strlen($data['password']) < 8 || !preg_match("/[a-z]/i", $data['password']) || !preg_match("/[0-9]/", $data['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters and contain both letters and numbers']);
            return;
        }

        try {
            $userModel = new User();
            
            if ($userModel->exists($data['email'])) {
                echo json_encode(['status' => 'error', 'message' => 'Email already registered']);
                return;
            }

            if ($userModel->register($data)) {
                $user = $userModel->findByEmail($data['email']);
                
                // Generate and Send OTP
                $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $userModel->setOtp($user['id'], $code);
                
                // Send Email
                $mailer = new SMTPMailer();
                $html = EmailTemplate::generate("Verify Account", "Welcome {$data['first_name']}!<br>Please verify your email address to get started.", $code);
                $mailer->send($data['email'], "Verify Account", $html);
                
                // Set Session for Verification
                Session::set('user_id', $user['id']);
                Session::set('is_verification_pending', true);
                Session::set('email', $user['email']);
                
                // Return 'verification_required' status instead of success/redirect
                echo json_encode(['status' => 'verification_required']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Registration failed due to database error.']);
            }
        } catch (\Exception $e) {
            error_log("Register Error: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    private function handleLogin() {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
            return;
        }

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            // Check if user is already verified
            if ($user['is_verified']) {
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
                
                // Device Fingerprinting: Check if this device is trusted
                if ($userModel->isDeviceTrusted($user['id'], $userAgent)) {
                    // Trusted Device -> Login Immediately
                    Session::set('user_id', $user['id']);
                    Session::set('is_logged_in', true);
                    Session::set('is_verified', true);
                    Session::set('email', $user['email']);
                    Session::set('username', $user['first_name']);
        
                    // Security: Clear Attempts & Log
                    Security::clearAttempts($_SERVER['REMOTE_ADDR']);
                    Security::log($user['id'], 'Login Success (Trusted Device)');
                    Session::regenerate();
                    
                    echo json_encode(['status' => 'success', 'redirect' => 'dashboard']);
                    return;
                }
                
                // UNKNOWN Device -> Treat as if unverified (Require OTP)
                // We will send a special "New Device" alert email
                $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $userModel->setOtp($user['id'], $code);
                
                Security::log($user['id'], 'New Device OTP Sent');
                
                try {
                    $mailer = new SMTPMailer();
                    $content = "We detected a login from a new device.<br><b>Device:</b> " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "<br><b>IP:</b> " . $_SERVER['REMOTE_ADDR'];
                    $html = EmailTemplate::generate("New Device Alert", $content, $code);
                    $mailer->send($user['email'], "Security Alert: New Device Detected", $html);
                } catch (\Exception $e) {
                    error_log("Login OTP Error: " . $e->getMessage());
                    echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP code.']);
                    return;
                }

                Session::set('user_id', $user['id']);
                Session::set('is_verification_pending', true);
                Session::set('email', $user['email']);
                echo json_encode(['status' => 'verification_required']);
                return;
            }

            // User is NOT verified -> Send OTP
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $userModel->setOtp($user['id'], $code);
        
            // Log Pre-Login Verification
            Security::log($user['id'], 'Login OTP Sent');
            
            // Send Email
            try {
                $mailer = new SMTPMailer();
                $html = EmailTemplate::generate("Verify Login", "Please enter the code below to complete your login.", $code);
                $mailer->send($user['email'], "Verify Account", $html);
            } catch (\Exception $e) {
                error_log("Login OTP Error: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP code.']);
                return;
            }
            
            // Set Session for Verification
            Session::set('user_id', $user['id']);
            $mailer = new SMTPMailer();
            $emailBody = "Your Verification Code is: {$code}";
            $mailer->send($user['email'], "Verify Account", $emailBody);
        } catch (\Exception $e) {
            error_log("Login OTP Error: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP code.']);
            return;
        }
        
        // Set Session for Verification
        Session::set('user_id', $user['id']);
        Session::set('is_verification_pending', true);
        Session::set('email', $user['email']);
        
        echo json_encode(['status' => 'verification_required']);
    } else {
        // Log Failure & Record Attempt
        Security::recordAttempt($_SERVER['REMOTE_ADDR']);
        // We don't have user ID, but we logged the IP in login_attempts
        
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    }    }
    
    public function logout() {
        Session::destroy();
        header('Location: login');
    }

    // --- Forgot Password ---

    public function showForgotPassword() {
        require_once ROOT_PATH . '/resources/views/auth/forgot-password.php';
    }

    public function handleForgotPassword() {
        header('Content-Type: application/json');
        
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
             echo json_encode(['status' => 'error', 'message' => 'Email is required']);
             return;
        }

        $userModel = new User();
        $token = $userModel->createPasswordResetToken($email);

        if ($token) {
            try {
                $resetLink = BASE_URL . "/reset-password?token=" . $token;
                $mailer = new SMTPMailer();
                $html = EmailTemplate::generate("Password Reset", "You requested a password reset. Click the button below to proceed.", null, $resetLink, "Reset Password");
                $mailer->send($email, "Password Reset", $html);
                
                echo json_encode(['status' => 'success', 'message' => 'Reset link sent! Check your email.']);
            } catch (\Exception $e) {
                 error_log("Reset PW Email Error: " . $e->getMessage());
                 echo json_encode(['status' => 'error', 'message' => 'Failed to send reset email.']);
            }
        } else {
            echo json_encode(['status' => 'success', 'message' => 'If an account exists, a reset link has been sent.']);
        }
    }

    public function showResetPassword() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
             die("Invalid or missing token.");
        }
        
        $userModel = new User();
        $user = $userModel->findByResetToken($token);

        if (!$user) {
             die("Invalid or expired token.");
        }

        require_once ROOT_PATH . '/resources/views/auth/reset-password.php';
    }

    public function handleResetPassword() {
        header('Content-Type: application/json');
        
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($token) || empty($password)) {
             echo json_encode(['status' => 'error', 'message' => 'Missing token or password']);
             return;
        }

        if (strlen($password) < 6) {
             echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters']);
             return;
        }

        $userModel = new User();
        $user = $userModel->findByResetToken($token);

        if ($user) {
            if ($userModel->resetPassword($user['id'], $password)) {
                echo json_encode(['status' => 'success', 'message' => 'Password updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token']);
        }
    }
}
