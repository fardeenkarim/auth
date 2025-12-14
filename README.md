# Secure Authentication System

A production-ready, high-performance authentication system built with PHP (Vanilla). It features "Banking Grade" security and "Silicon Valley" aesthetics.

## 🚀 Features

### Security
*   **CSRF Protection**: Comprehensive protection on all forms and API endpoints.
*   **Rate Limiting**: Login and OTP attempts are limited (5 attempts / 15 mins) to prevent brute force.
*   **Device Fingerprinting**: Detects new devices/browsers and triggers an additional OTP verification.
*   **Audit Logging**: Tracks all security events (Login Success/Fail, New Device, etc.).
*   **Secure Sessions**: `HttpOnly`, `SameSite=Lax`, and `Secure` cookies with session ID regeneration.
*   **Input Sanitization**: Strict validation for all user inputs.

### Performance
*   **Instant UI**: Asynchronous email sending (Background Process) ensures sub-100ms API response times.
*   **Optimized Assets**: Minimalist, fast-loading frontend.

### Design
*   **Premium Emails**: HTML email templates inspired by Linear/Vercel (Minimalist, Spaced, Mobile-Optimized).
*   **Modern UI**: Clean dashboard and auth pages.

## 🛠 Installation

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/fardeenkarim/auth.git
    cd auth
    ```

2.  **Database Setup**
    *   Creates a MySQL database (e.g., `auth_system`).
    *   Import `database/schema.sql`.

3.  **Configuration**
    *   Copy `.env.example` to `.env`.
    *   Update DB credentials and SMTP settings.
    ```bash
    cp .env.example .env
    ```

4.  **Directory Permissions**
    *   Ensure `app/jobs` script is executable (if on Linux/Mac).
    ```bash
    chmod +x app/jobs/send_mail_job.php
    ```

## ⚠️ Production Notes

*   **Email Queue**: The system uses `exec()` to spawn a background PHP process for sending emails. Ensure `php` is in your system's PATH, or update `app/Helpers/AsyncMailer.php` with the absolute path to your PHP binary.
*   **Web Server**: Ensure your web server (Apache/Nginx) is configured to deny access to the `app/` directory (a `.htaccess` file is included for Apache).

##  License
MIT
