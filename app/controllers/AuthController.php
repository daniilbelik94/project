<?php

namespace Controllers;

use Core\Controller;
use Core\View;

require_once __DIR__ . '/../models/User.php';

use \User;

class AuthController extends Controller
{
    public function registerForm()
    {
        $this->view('auth/register');
    }

    public function loginForm()
    {
        $this->view('auth/login');
    }

    public function register()
    {
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        // Обработка регистрации
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = 'spieler';

        if (!$name || !$email || !$password) {
            $error = 'Alle Felder sind erforderlich!';
            return $this->view('auth/register', compact('error'));
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            set_flash('E-Mail bereits registriert!', 'error');
            return $this->view('auth/register', compact('error'));
        }
        $userModel->create($name, $email, $hash, $role);
        set_flash('Registrierung erfolgreich! Bitte einloggen.', 'success');
        header('Location: /login');
        exit;
    }

    public function login()
    {
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            set_flash('Login erfolgreich!', 'success');
            header('Location: /home');
            exit;
        } else {
            set_flash('Falsche Zugangsdaten!', 'error');
            return $this->view('auth/login', compact('error'));
        }
    }

    public function logout()
    {
        require_once __DIR__ . '/../core/flash.php';
        session_start();
        session_destroy();
        set_flash('Logout erfolgreich!', 'success');
        header('Location: /login');
        exit;
    }
}
