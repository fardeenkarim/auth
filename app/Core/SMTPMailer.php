<?php
namespace App\Core;

class SMTPMailer {
    public function send($to, $subject, $body) {
        $host = MAIL_HOST;
        $port = MAIL_PORT;
        $username = MAIL_USERNAME;
        $password = MAIL_PASSWORD;
        $from = MAIL_FROM_ADDRESS;
        $fromName = MAIL_FROM_NAME;

        // SSL wrapper for port 465
        if ($port == 465) {
            $host = "ssl://" . $host;
        }

        $socket = fsockopen($host, $port, $errno, $errstr, 15);
        if (!$socket) {
             throw new \Exception("Could not connect to SMTP host: $errno $errstr");
        }

        $this->readResponse($socket); // Initial greeting

        $this->put($socket, "EHLO " . gethostname());
        $this->readResponse($socket); // 250...

        $this->put($socket, "AUTH LOGIN");
        $this->readResponse($socket, "334");
        
        $this->put($socket, base64_encode($username));
        $this->readResponse($socket, "334");
        
        $this->put($socket, base64_encode($password));
        $this->readResponse($socket, "235");

        $this->put($socket, "MAIL FROM: <$from>");
        $this->readResponse($socket, "250");
        
        $this->put($socket, "RCPT TO: <$to>");
        $this->readResponse($socket, "250");

        $this->put($socket, "DATA");
        $this->readResponse($socket, "354");

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $fromName <$from>\r\n";
        $headers .= "To: <$to>\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "Date: " . date("r") . "\r\n";

        $this->put($socket, $headers . "\r\n" . $body . "\r\n.");
        $this->readResponse($socket, "250");

        $this->put($socket, "QUIT");
        $this->readResponse($socket, "221");

        fclose($socket);
        return true;
    }

    private function put($socket, $cmd) {
        fputs($socket, $cmd . "\r\n");
    }

    private function readResponse($socket, $expected = null) {
        $response = "";
        while (substr($response, 3, 1) != " ") {
            $line = fgets($socket, 512);
            if ($line === false) break;
            $response = $line;
        }
        
        if ($expected && substr($response, 0, 3) != $expected) {
            throw new \Exception("SMTP Error: $response");
        }
        return $response;
    }
}
