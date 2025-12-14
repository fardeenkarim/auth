<?php
namespace App\Models;

use App\Core\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($data) {
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, whatsapp_number, location, password, is_verified) VALUES (:first_name, :last_name, :email, :whatsapp_number, :location, :password, 0)");
        
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':whatsapp_number', $data['whatsapp_number']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':password', $hashed_password);
        
        return $stmt->execute();
    }

    public function login($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function exists($email) {
        return $this->findByEmail($email) !== false;
    }

    public function updateRememberToken($userId, $token) {
        // We store the hash of the token for security
        $hash = hash('sha256', $token);
        $stmt = $this->db->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        $stmt->bindParam(':token', $hash);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function findByRememberToken($token) {
        // Search by hash
        $hash = hash('sha256', $token);
        $stmt = $this->db->prepare("SELECT * FROM users WHERE remember_token = :token");
        $stmt->bindParam(':token', $hash);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createPasswordResetToken($email) {
        $user = $this->findByEmail($email);
        if (!$user) return false;

        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        
        // Use Database Time for consistency
        $stmt = $this->db->prepare("UPDATE users SET reset_token_hash = :hash, reset_token_expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':email', $email);
        
        if ($stmt->execute()) {
            return $token;
        }
        return false;
    }

    public function findByResetToken($token) {
        $hash = hash('sha256', $token);
        $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token_hash = :hash AND reset_token_expires_at > NOW()");
        $stmt->bindParam(':hash', $hash);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function resetPassword($userId, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = :password, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = :id");
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function setOtp($userId, $code) {
        // Use Database Time to avoid Timezone Mismatches between PHP and MySQL
        $stmt = $this->db->prepare("UPDATE users SET otp_code = :code, otp_expires_at = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE id = :id");
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function verifyOtp($userId, $code) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id AND otp_code = :code AND otp_expires_at > NOW()");
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function markAsVerified($userId) {
        $stmt = $this->db->prepare("UPDATE users SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    // --- Device Fingerprinting ---

    public function isDeviceTrusted($userId, $userAgent) {
        // We check User Agent. Checking IP might be too strict for mobile users (changing IPs).
        // Let's check User Agent primarily.
        // Ideally we should use a long-lived cookie, but User Agent + IP tracking is a good server-side start.
        // For "Banking Grade", banks usually track "New Device" by cookie or UA.
        // Let's match User Agent exactly.
        
        $stmt = $this->db->prepare("SELECT id FROM user_devices WHERE user_id = :id AND user_agent = :ua");
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':ua', $userAgent);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    public function addTrustedDevice($userId, $userAgent, $ip) {
        // limit number of devices? maybe later.
        $stmt = $this->db->prepare("INSERT INTO user_devices (user_id, user_agent, ip_address) VALUES (:id, :ua, :ip)");
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':ua', $userAgent);
        $stmt->bindParam(':ip', $ip);
        return $stmt->execute();
    }
}
