<?php
namespace App\Helpers;

class EmailTemplate {
    public static function generate($subject, $content, $code = null, $ctaLink = null, $ctaText = null) {
        $appName = APP_NAME;
        $year = date('Y');
        
        $spacer = "<div style='height: 50px; line-height: 50px; font-size: 50px;'>&nbsp;</div>";
        
        $codeBlock = '';
        if ($code) {
             $codeBlock = "
             $spacer
             <div style='text-align: center;'>
                <span style='display: inline-block; font-family: monospace; font-size: 36px; font-weight: 700; letter-spacing: 8px; color: #111827; background: #F3F4F6; padding: 24px 48px; border-radius: 12px; border: 1px dashed #D1D5DB;'>
                    $code
                </span>
             </div>
             $spacer";
        }

        $button = '';
        if ($ctaLink && $ctaText) {
            $button = "
            $spacer
            <div style='text-align: center;'>
                <a href='$ctaLink' style='background-color: #000000; color: white; padding: 18px 36px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: inline-block;'>
                    $ctaText
                </a>
            </div>
            $spacer";
        }

        return "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$subject</title>
</head>
<body style='margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \"Inter\", \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; background-color: #F9FAFB; color: #374151; -webkit-font-smoothing: antialiased;'>
    <div style='max-width: 500px; margin: 60px auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #E5E7EB; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); overflow: hidden;'>
        
        <!-- Minimal Header with Logo Placeholder -->
        <div style='padding: 30px 40px; border-bottom: 1px solid #F3F4F6;'>
            <div style='font-size: 18px; font-weight: 700; color: #111827; letter-spacing: -0.5px;'>
                ⚡️ $appName
            </div>
        </div>

        <!-- Content -->
        <div style='padding: 40px 40px;'>
            <h1 style='margin: 0 0 20px; color: #111827; font-size: 24px; font-weight: 700; letter-spacing: -0.5px; line-height: 1.3;'>
                $subject
            </h1>
            
            <div style='color: #4B5563; font-size: 16px; line-height: 1.6;'>
                $content
            </div>
            
            $codeBlock
            $button

            <div style='margin-top: 40px; border-top: 1px solid #F3F4F6; padding-top: 20px;'>
                <p style='font-size: 13px; color: #9CA3AF; line-height: 1.5; margin: 0;'>
                    If you did not request this, please ignore this email. This link or code will expire in 15 minutes.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style='background-color: #F9FAFB; padding: 20px 40px; text-align: center; font-size: 12px; color: #9CA3AF;'>
            &copy; $year $appName, Inc. All rights reserved. <br>
            <span style='margin-top: 5px; display: block;'>Jakarta, Indonesia</span>
        </div>
    </div>
</body>
</html>
";
    }
}
