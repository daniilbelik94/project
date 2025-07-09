<?php
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) require_once $path;
});
require_once __DIR__ . '/../app/models/User.php';

function assertEqual($a, $b, $msg)
{
    if ($a === $b) {
        echo "[OK] $msg\n";
    } else {
        echo "[FAIL] $msg\n  Expected: " . $b . "\n  Got: " . $a . "\n";
    }
}

$userModel = new User();
$email = 'testuser_' . rand(1000, 9999) . '@example.com';
$name = 'Test User';
$pass = 'testpass123';
$role = 'spieler';

// 1. Создание пользователя
$hash = password_hash($pass, PASSWORD_BCRYPT);
$userModel->create($name, $email, $hash, $role);
$user = $userModel->findByEmail($email);
assertEqual($user['email'], $email, 'User created and found by email');

// 2. Проверка пароля
assertEqual(password_verify($pass, $user['password_hash']), true, 'Password verify works');

// 3. Смена пароля
$newPass = 'newpass456';
$newHash = password_hash($newPass, PASSWORD_BCRYPT);
$userModel->update($user['id'], $user['name'], $user['email'], $user['role']); // just to test update
$stmt = User::getDb()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
$stmt->execute([$newHash, $user['id']]);
$user2 = $userModel->findByEmail($email);
assertEqual(password_verify($newPass, $user2['password_hash']), true, 'Password changed and verified');

// 4. Удаление пользователя
$userModel->delete($user['id']);
$user3 = $userModel->findByEmail($email);
assertEqual($user3, false, 'User deleted');