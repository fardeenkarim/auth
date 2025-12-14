<?php
// jobs/send_mail_job.php
// This script is called from the command line to send email in the background

if (php_sapi_name() !== 'cli') {
    die('Access Denied');
}

require_once __DIR__ . '/../Core/Env.php';
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/SMTPMailer.php';

use App\Core\SMTPMailer;

// Arguments: 1=To, 2=Subject, 3=BodyHTML
if ($argc < 4) {
    exit;
}

$to = $argv[1];
$subject = $argv[2];
$body = $argv[3]; // Passed as base64 encoded to avoid shell issues

$body = base64_decode($body);

try {
    $mailer = new SMTPMailer();
    $mailer->send($to, $subject, $body);
    // Logging optionally
} catch (Exception $e) {
    // Log failure
    file_put_contents(__DIR__ . '/../../debug_error.log', date('[Y-m-d H:i:s] ') . "Async Mail Fail: " . $e->getMessage() . "\n", FILE_APPEND);
}
