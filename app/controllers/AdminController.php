<?php

namespace Controllers;

use Core\Controller;
use \User;

require_once __DIR__ . '/../models/User.php';

class AdminController extends Controller
{
    public function index()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        $userModel = new User();
        $users = $userModel->getAll();
        $this->view('admin/index', compact('users'));
    }

    public function createForm()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        $this->view('admin/create');
    }

    public function create()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'spieler';
        $error = '';
        if (!$name || !$email || !$password || !$role) {
            $error = 'Alle Felder sind erforderlich!';
            return $this->view('admin/create', compact('error'));
        }
        $userModel = new \User();
        if ($userModel->findByEmail($email)) {
            set_flash('E-Mail bereits registriert!', 'error');
            return $this->view('admin/create', compact('error'));
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $userModel->create($name, $email, $hash, $role);
        set_flash('Benutzer erfolgreich hinzugefügt!', 'success');
        header('Location: /admin');
        exit;
    }

    public function editForm()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin');
            exit;
        }
        $userModel = new \User();
        $user = $userModel->findById($id);
        if (!$user) {
            header('Location: /admin');
            exit;
        }
        $this->view('admin/edit', compact('user'));
    }

    public function edit()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'spieler';
        $error = '';
        if (!$id || !$name || !$email || !$role) {
            $error = 'Alle Felder sind erforderlich!';
            $user = ['id' => $id, 'name' => $name, 'email' => $email, 'role' => $role];
            return $this->view('admin/edit', compact('user', 'error'));
        }
        $userModel = new \User();
        $userModel->update($id, $name, $email, $role);
        set_flash('Benutzerdaten gespeichert!', 'success');
        header('Location: /admin');
        exit;
    }

    public function delete()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../core/flash.php';
        $id = $_GET['id'] ?? null;
        if ($id) {
            $userModel = new \User();
            $userModel->delete($id);
            set_flash('Benutzer gelöscht.', 'success');
        }
        header('Location: /admin');
        exit;
    }

    public function finances()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../models/FinanceModel.php';
        $financeModel = new \FinanceModel();
        $finances = $financeModel->getAll();
        $this->view('admin/finances', compact('finances'));
    }

    public function addFinanceForm()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        $this->view('admin/add_finance');
    }

    public function addFinance()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        $type = $_POST['type'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $date = $_POST['date'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $error = '';
        if (!$type || !$amount || !$date || !$description) {
            $error = 'Alle Felder sind erforderlich!';
            return $this->view('admin/add_finance', compact('error'));
        }
        require_once __DIR__ . '/../models/FinanceModel.php';
        $financeModel = new \FinanceModel();
        $financeModel->create($type, $amount, $date, $description);
        set_flash('Buchung gespeichert!', 'success');
        header('Location: /admin/finances');
        exit;
    }

    public function matches()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../models/MatchModel.php';
        $matchModel = new \MatchModel();
        $matches = $matchModel->getAll();
        $this->view('admin/matches', compact('matches'));
    }

    public function addMatch()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        $date = $_POST['date'] ?? '';
        $away_team = trim($_POST['away_team'] ?? '');
        $result = trim($_POST['result'] ?? '');
        $away_team_avatar = null;
        // Приоритет: файл > url
        if (isset($_FILES['away_team_avatar_file']) && $_FILES['away_team_avatar_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['away_team_avatar_file'];
            if ($file['size'] > 2 * 1024 * 1024) {
                set_flash('Datei zu groß (max. 2MB).', 'error');
            } else {
                $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                if (!in_array($file['type'], $allowed)) {
                    set_flash('Ungültiger Dateityp.', 'error');
                } else {
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = 'away_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $target = __DIR__ . '/../../public/uploads/avatars/' . $filename;
                    if (!is_dir(__DIR__ . '/../../public/uploads/avatars/')) {
                        mkdir(__DIR__ . '/../../public/uploads/avatars/', 0777, true);
                    }
                    if (move_uploaded_file($file['tmp_name'], $target)) {
                        $away_team_avatar = $filename;
                    } else {
                        set_flash('Upload fehlgeschlagen.', 'error');
                    }
                }
            }
        } elseif (!empty($_POST['away_team_avatar_url'])) {
            $url = trim($_POST['away_team_avatar_url']);
            $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                set_flash('Nur JPG, PNG, WEBP erlaubt.', 'error');
            } else {
                $img = @file_get_contents($url);
                if ($img === false) {
                    set_flash('Bild konnte nicht geladen werden.', 'error');
                } else if (strlen($img) > 2 * 1024 * 1024) {
                    set_flash('Datei zu groß (max. 2MB).', 'error');
                } else {
                    // Можно хранить как URL, если не хотим скачивать: $away_team_avatar = $url;
                    // Но для единообразия — скачиваем и сохраняем локально:
                    $filename = 'away_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $target = __DIR__ . '/../../public/uploads/avatars/' . $filename;
                    if (!is_dir(__DIR__ . '/../../public/uploads/avatars/')) {
                        mkdir(__DIR__ . '/../../public/uploads/avatars/', 0777, true);
                    }
                    file_put_contents($target, $img);
                    $away_team_avatar = $filename;
                }
            }
        }
        if (!$date || !$away_team) {
            set_flash('Alle Felder sind erforderlich!', 'error');
            header('Location: /admin/matches');
            exit;
        }
        require_once __DIR__ . '/../models/MatchModel.php';
        $matchModel = new \MatchModel();
        $matchModel->createMatch($date, 'FC Musterstadt', $away_team, $result, $away_team_avatar);
        set_flash('Spiel erfolgreich hinzugefügt!', 'success');
        header('Location: /admin/matches');
        exit;
    }

    public function editResultForm()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/matches');
            exit;
        }
        require_once __DIR__ . '/../models/MatchModel.php';
        $matchModel = new \MatchModel();
        $match = $matchModel->findById($id);
        if (!$match) {
            header('Location: /admin/matches');
            exit;
        }
        $this->view('admin/edit_result', compact('match'));
    }

    public function editResult()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../core/csrf.php';
        require_once __DIR__ . '/../core/flash.php';
        csrf_check();
        $id = $_POST['id'] ?? null;
        $result = trim($_POST['result'] ?? '');
        if (!$id) {
            header('Location: /admin/matches');
            exit;
        }
        require_once __DIR__ . '/../models/MatchModel.php';
        $matchModel = new \MatchModel();
        $matchModel->updateResult($id, $result);
        set_flash('Ergebnis gespeichert!', 'success');
        header('Location: /admin/matches');
        exit;
    }

    public function searchUsers()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit;
        }
        $query = trim($_GET['query'] ?? '');
        require_once __DIR__ . '/../models/User.php';
        $userModel = new \User();
        $users = $userModel->search($query);
        header('Content-Type: application/json');
        echo json_encode($users);
        exit;
    }

    public function usersPaginated()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit;
        }
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $query = trim($_GET['query'] ?? '');
        require_once __DIR__ . '/../models/User.php';
        $userModel = new \User();
        $users = $userModel->searchPaginated($query, $limit, $offset);
        $total = $userModel->countSearch($query);
        header('Content-Type: application/json');
        echo json_encode(['users' => $users, 'total' => $total]);
        exit;
    }

    public function matchesPaginated()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit;
        }
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        require_once __DIR__ . '/../models/MatchModel.php';
        $matchModel = new \MatchModel();
        $matches = $matchModel->getPaginated($limit, $offset);
        $total = $matchModel->countAll();
        header('Content-Type: application/json');
        echo json_encode(['matches' => $matches, 'total' => $total]);
        exit;
    }

    public function financesPaginated()
    {
        session_start();
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit;
        }
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        require_once __DIR__ . '/../models/FinanceModel.php';
        $financeModel = new \FinanceModel();
        $finances = $financeModel->getPaginated($limit, $offset);
        $total = $financeModel->countAll();
        header('Content-Type: application/json');
        echo json_encode(['finances' => $finances, 'total' => $total]);
        exit;
    }
}
