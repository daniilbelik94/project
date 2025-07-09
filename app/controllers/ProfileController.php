<?php

namespace Controllers;

use Core\Controller;
use Core\View;
use \User;
use \DocumentModel;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/DocumentModel.php';

class ProfileController extends Controller
{
    public function index()
    {
        session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        $docModel = new DocumentModel();
        $order = (isset($_GET['order']) && strtolower($_GET['order']) === 'asc') ? 'ASC' : 'DESC';
        $documents = $docModel->getByUser($_SESSION['user_id'], $order);
        $this->view('profile/index', compact('user', 'documents', 'order'));
    }

    public function upload()
    {
        require_once __DIR__ . '/../core/csrf.php';
        csrf_check();
        session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $error = '';
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Fehler beim Upload.';
        } else {
            $file = $_FILES['document'];
            if ($file['size'] > 5 * 1024 * 1024) {
                $error = 'Datei zu groß (max. 5MB).';
            } else {
                $allowed = ['application/pdf', 'image/jpeg', 'image/png'];
                if (!in_array($file['type'], $allowed)) {
                    $error = 'Ungültiger Dateityp.';
                } else {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = uniqid('doc_') . '.' . $ext;
                    $target = __DIR__ . '/../../public/uploads/' . $filename;
                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        $docModel = new DocumentModel();
                        $docModel->create($_SESSION['user_id'], $filename);
                    } else {
                        $error = 'Upload fehlgeschlagen.';
                    }
                }
            }
        }
        if ($error) {
            $_SESSION['upload_error'] = $error;
        }
        header('Location: /profil');
        exit;
    }

    public function avatarUpload()
    {
        require_once __DIR__ . '/../core/csrf.php';
        csrf_check();
        session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $error = '';
        if (!isset($_FILES['avatar_file']) || $_FILES['avatar_file']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Fehler beim Upload.';
        } else {
            $file = $_FILES['avatar_file'];
            if ($file['size'] > 2 * 1024 * 1024) {
                $error = 'Datei zu groß (max. 2MB).';
            } else {
                $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                if (!in_array($file['type'], $allowed)) {
                    $error = 'Ungültiger Dateityp.';
                } else {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                    $target = __DIR__ . '/../../public/uploads/avatars/' . $filename;
                    if (!is_dir(__DIR__ . '/../../public/uploads/avatars/')) {
                        mkdir(__DIR__ . '/../../public/uploads/avatars/', 0777, true);
                    }
                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        $userModel = new \User();
                        $userModel->updateAvatar($_SESSION['user_id'], $filename);
                    } else {
                        $error = 'Upload fehlgeschlagen.';
                    }
                }
            }
        }
        if ($error) {
            $_SESSION['avatar_error'] = $error;
        }
        header('Location: /profil');
        exit;
    }

    public function avatarUrl()
    {
        require_once __DIR__ . '/../core/csrf.php';
        csrf_check();
        session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $error = '';
        $url = trim($_POST['avatar_url'] ?? '');
        if (!$url) {
            $error = 'Bitte URL angeben.';
        } else {
            $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $error = 'Nur JPG, PNG, WEBP erlaubt.';
            } else {
                $img = @file_get_contents($url);
                if ($img === false) {
                    $error = 'Bild konnte nicht geladen werden.';
                } else if (strlen($img) > 2 * 1024 * 1024) {
                    $error = 'Datei zu groß (max. 2MB).';
                } else {
                    $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                    $target = __DIR__ . '/../../public/uploads/avatars/' . $filename;
                    if (!is_dir(__DIR__ . '/../../public/uploads/avatars/')) {
                        mkdir(__DIR__ . '/../../public/uploads/avatars/', 0777, true);
                    }
                    file_put_contents($target, $img);
                    $userModel = new \User();
                    $userModel->updateAvatar($_SESSION['user_id'], $filename);
                }
            }
        }
        if ($error) {
            $_SESSION['avatar_error'] = $error;
        }
        header('Location: /profil');
        exit;
    }

    public function changePassword()
    {
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $userModel = new \User(); // инициализация self::$db
        $user = $userModel->findById($_SESSION['user_id']);
        if (!$current || !$new || !$confirm) {
            set_flash('Alle Felder sind erforderlich.', 'error');
        } elseif (!password_verify($current, $user['password_hash'])) {
            set_flash('Falsches aktuelles Passwort.', 'error');
        } elseif (strlen($new) < 6) {
            set_flash('Neues Passwort zu kurz (min. 6 Zeichen).', 'error');
        } elseif ($new !== $confirm) {
            set_flash('Passwörter stimmen nicht überein.', 'error');
        } else {
            $hash = password_hash($new, PASSWORD_BCRYPT);
            $stmt = \User::getDb()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $stmt->execute([$hash, $_SESSION['user_id']]);
            set_flash('Passwort erfolgreich geändert!', 'success');
        }
        header('Location: /profil');
        exit;
    }

    public function changeEmail()
    {
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $new = trim($_POST['new_email'] ?? '');
        $current = $_POST['current_password'] ?? '';
        $userModel = new \User();
        $user = $userModel->findById($_SESSION['user_id']);
        if (!$new || !$current) {
            set_flash('Alle Felder sind erforderlich.', 'error');
        } elseif (!filter_var($new, FILTER_VALIDATE_EMAIL)) {
            set_flash('Ungültige E-Mail.', 'error');
        } elseif (!password_verify($current, $user['password_hash'])) {
            set_flash('Falsches aktuelles Passwort.', 'error');
        } else {
            $stmt = \User::getDb()->prepare('UPDATE users SET email = ? WHERE id = ?');
            $stmt->execute([$new, $_SESSION['user_id']]);
            $_SESSION['email'] = $new;
            set_flash('E-Mail erfolgreich geändert!', 'success');
        }
        header('Location: /profil');
        exit;
    }
}
