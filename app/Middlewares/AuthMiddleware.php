<?php
namespace App\Middlewares;

use App\Core\Session;

class AuthMiddleware {
    public function handle() {
        if (!Session::get('is_logged_in')) {
            header('Location: login');
            exit;
        }
    }
}
