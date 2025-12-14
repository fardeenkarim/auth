<?php
namespace App\Core;

class Security {
    
    // Rate Limit Config
    const MAX_ATTEMPTS = 5;
    const LOCKOUT_TIME = 15; // Minutes

    public static function checkRateLimit($ip) {
        $db = Database::getInstance()->getConnection();
        
        // Count attempts in the last 15 minutes
        $stmt = $db->prepare("SELECT count(*) as count FROM login_attempts WHERE ip_address = :ip AND attempt_time > (NOW() - INTERVAL 15 MINUTE)");
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
        $count = $stmt->fetch()['count'];

        return $count < self::MAX_ATTEMPTS;
    }

    public static function recordAttempt($ip) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO login_attempts (ip_address) VALUES (:ip)");
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
    }
    
    public static function clearAttempts($ip) {
        // Optional: Reset attempts on successful login, or just let them expire naturally
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM login_attempts WHERE ip_address = :ip");
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
    }

    public static function log($userId, $action, $details = null) {
        $db = Database::getInstance()->getConnection();
        $ip = $_SERVER['REMOTE_ADDR'];
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        $stmt = $db->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address, user_agent) VALUES (:uid, :action, :details, :ip, :ua)");
        $stmt->bindParam(':uid', $userId);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':ua', $ua);
        $stmt->execute();
    }
}
