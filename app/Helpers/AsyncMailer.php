<?php
namespace App\Helpers;

class AsyncMailer {
    public static function send($to, $subject, $body) {
        $scriptPath = __DIR__ . '/../jobs/send_mail_job.php';
        $encodedBody = base64_encode($body);
        
        // Escape shell args just in case, though base64 is safe
        $to = escapeshellarg($to);
        $subject = escapeshellarg($subject);
        $body = escapeshellarg($encodedBody);
        
        // Run in background ( > /dev/null 2>&1 & )
        // Using XAMPP PHP Path explicitly for Mac
        $phpPath = '/Applications/XAMPP/xamppfiles/bin/php';
        
        $cmd = "$phpPath $scriptPath $to $subject $body > /dev/null 2>&1 &";
        
        // Check OS to ensure proper background execution
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen("start /B php $scriptPath $to $subject $body", "r"));
        } else {
            exec($cmd);
        }
    }
}
