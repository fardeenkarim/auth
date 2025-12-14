<?php
namespace App\Controllers;

use App\Core\Session;
use App\Models\User;

class DashboardController {
    public function index() {
        if (!Session::get('is_logged_in')) {
            header('Location: login');
            exit;
        }
        
        $user = [
            'id' => Session::get('user_id'),
            'username' => Session::get('username'),
            'email' => Session::get('email')
        ];
        
        // Render View
        require_once ROOT_PATH . '/resources/views/dashboard/index.php';
    }
}
