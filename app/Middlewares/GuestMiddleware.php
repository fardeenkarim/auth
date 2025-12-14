<?php
namespace App\Middlewares;

use App\Core\Session;

class GuestMiddleware {
    public function handle() {
        if (Session::get('is_logged_in')) {
            header('Location: dashboard');
            exit;
        }
    }
}
