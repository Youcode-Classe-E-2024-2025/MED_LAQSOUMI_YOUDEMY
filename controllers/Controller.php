<?php

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../views/{$view}.php";
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function getUserRole() {
        return $_SESSION['role'] ?? null;
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }

    protected function requireRole($role) {
        if ($this->getUserRole() !== $role) {
            $this->redirect('/unauthorized');
        }
    }
}
