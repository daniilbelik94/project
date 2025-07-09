<?php


spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

if ($uri === '' || $uri === 'index.php') {
    include __DIR__ . '/welcome.php';
    exit;
}


if ($uri === 'home') {
    $controller = new \Controllers\HomeController();
    $controller->index();
    exit;
}

// Роутинг для регистрации и входа
if ($uri === 'register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AuthController();
    $controller->registerForm();
    exit;
}
if ($uri === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\AuthController();
    $controller->register();
    exit;
}
if ($uri === 'login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AuthController();
    $controller->loginForm();
    exit;
}
if ($uri === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\AuthController();
    $controller->login();
    exit;
}
if ($uri === 'logout') {
    $controller = new \Controllers\AuthController();
    $controller->logout();
    exit;
}


if ($uri === 'spielplan') {
    $controller = new \Controllers\MatchController();
    $controller->index();
    exit;
}


if ($uri === 'profil' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\ProfileController();
    $controller->index();
    exit;
}
if ($uri === 'profil/upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\ProfileController();
    $controller->upload();
    exit;
}
if ($uri === 'profil/avatar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\ProfileController();
    $controller->avatarUpload();
    exit;
}
if ($uri === 'profil/avatar-url' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\ProfileController();
    $controller->avatarUrl();
    exit;
}
if ($uri === 'profil/change-password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\ProfileController();
    $controller->changePassword();
    exit;
}
if ($uri === 'profil/change-email' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\ProfileController();
    $controller->changeEmail();
    exit;
}


if ($uri === 'admin' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->index();
    exit;
}

if ($uri === 'admin/create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->createForm();
    exit;
}
if ($uri === 'admin/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\AdminController();
    $controller->create();
    exit;
}

if ($uri === 'admin/edit' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->editForm();
    exit;
}
if ($uri === 'admin/edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\AdminController();
    $controller->edit();
    exit;
}
if ($uri === 'admin/delete' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->delete();
    exit;
}

if ($uri === 'admin/finances' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->finances();
    exit;
}
if ($uri === 'admin/finances/add' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->addFinanceForm();
    exit;
}
if ($uri === 'admin/finances/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\AdminController();
    $controller->addFinance();
    exit;
}

if ($uri === 'admin/matches' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->matches();
    exit;
}
if ($uri === 'admin/matches/edit' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->editResultForm();
    exit;
}
if ($uri === 'admin/matches/edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\AdminController();
    $controller->editResult();
    exit;
}
if ($uri === 'admin/matches/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \Controllers\AdminController();
    $controller->addMatch();
    exit;
}

if ($uri === 'admin/search-users' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->searchUsers();
    exit;
}

if ($uri === 'admin/users-paginated' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->usersPaginated();
    exit;
}

if ($uri === 'admin/matches-paginated' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->matchesPaginated();
    exit;
}
if ($uri === 'admin/finances-paginated' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \Controllers\AdminController();
    $controller->financesPaginated();
    exit;
}

if ($uri === 'team') {
    $controller = new \Controllers\TeamController();
    $controller->index();
    exit;
}

http_response_code(404);
echo 'Seite nicht gefunden.';