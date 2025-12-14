<?php
namespace App\Helpers;

class EmailTemplate {
    public static function generate($subject, $content, $code = null, $ctaLink = null, $ctaText = null) {
        $appName = APP_NAME;
        $year = date('Y');
        
        $codeBlock = '';
        if ($code) {
             $codeBlock = "
             <div style='text-align: center; margin: 30px 0;'>
                <span style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #4F46E5; background: #EEF2FF; padding: 15px 30px; border-radius: 8px; border: 1px solid #E0E7FF;'>
                    $code
                </span>
             </div>";
        }

        $button = '';
        if ($ctaLink && $ctaText) {
            $button = "
            <div style='text-align: center; margin: 30px 0;'>
                <a href='$ctaLink' style='background-color: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;'>
                    $ctaText
                </a>
            </div>";
        }

        return "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$subject</title>
</head>
<body style='margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937;'>
    <div style='max-width: 600px; margin: 40px auto; background-color: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);'>
        <!-- Header -->
        <div style='background-color: #4F46E5; padding: 30px; text-align: center;'>
            <h1 style='margin: 0; color: white; font-size: 24px; font-weight: 700;'>$appName</h1>
        </div>

        <!-- Content -->
        <div style='padding: 40px 30px;'>
            <h2 style='margin-top: 0; color: #111827; font-size: 20px; font-weight: 600;'>$subject</h2>
            <div style='color: #4b5563; font-size: 16px; line-height: 1.6; margin-top: 20px;'>
                $content
            </div>
            
            $codeBlock
            $button

            <div style='margin-top: 30px; font-size: 14px; color: #6b7280; font-style: italic;'>
                If you did not request this, please ignore this email or contact support if you have concerns.
            </div>
        </div>

        <!-- Footer -->
        <div style='background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb;'>
            &copy; $year $appName. All rights reserved.
        </div>
    </div>
</body>
</html>
";
    }
}
