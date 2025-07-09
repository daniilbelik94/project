<?php
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) require_once $path;
});
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/DocumentModel.php';

function assertEqual($a, $b, $msg)
{
    if ($a === $b) {
        echo "[OK] $msg\n";
    } else {
        echo "[FAIL] $msg\n  Expected: " . $b . "\n  Got: " . $a . "\n";
    }
}

$userModel = new User();
$docModel = new DocumentModel();
$email = 'testdoc_' . rand(1000, 9999) . '@example.com';
$name = 'Test DocUser';
$pass = 'testpass123';
$role = 'spieler';
$hash = password_hash($pass, PASSWORD_BCRYPT);
$userModel->create($name, $email, $hash, $role);
$user = $userModel->findByEmail($email);

// 1. Создание документа
$filename = 'testfile_' . rand(1000, 9999) . '.pdf';
$docModel->create($user['id'], $filename);
$docs = $docModel->getByUser($user['id']);
assertEqual(count($docs) > 0, true, 'Document created and found');

// 2. Проверка данных документа
$found = false;
foreach ($docs as $d) {
    if ($d['filename'] === $filename) $found = true;
}
assertEqual($found, true, 'Document filename matches');

// 3. Очистка: удалить пользователя (каскадно удалит документы)
$userModel->delete($user['id']);
$docs2 = $docModel->getByUser($user['id']);
assertEqual(count($docs2), 0, 'Documents deleted with user');