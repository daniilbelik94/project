<?php
require_once __DIR__ . '/../../core/flash.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$isAdmin = !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';
$inAdmin = strpos($_SERVER['REQUEST_URI'], '/admin') === 0;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/home">
            <img src="https://media.teamfanapp.com/clubs/1/logo.png" alt="FC Musterstadt" width="40" height="40" class="me-2 rounded-circle" style="object-fit:cover;">
            <span class="fw-bold">FC Musterstadt</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php
                if (!empty($_SESSION['user_id'])) {
                    require_once __DIR__ . '/../../models/User.php';
                    $userModel = new \User();
                    $headerUser = $userModel->findById($_SESSION['user_id']);
                }
                ?>
                <?php if ($isAdmin && $inAdmin): ?>
                    <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/admin' ? ' active' : '' ?>" href="/admin">Benutzer</a></li>
                    <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/admin/finances' ? ' active' : '' ?>" href="/admin/finances">Finanzen</a></li>
                    <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/admin/matches' ? ' active' : '' ?>" href="/admin/matches">Spiele</a></li>
                    <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/team' ? ' active' : '' ?>" href="/team">Team</a></li>
                    <li class="nav-item"><a class="nav-link" href="/home">Zurück zum Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/spielplan' ? ' active' : '' ?>" href="/spielplan">Spielplan</a></li>
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/profil' ? ' active' : '' ?>" href="/profil">Mein Profil</a></li>
                        <?php if ($isAdmin): ?>
                            <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/admin' ? ' active' : '' ?>" href="/admin">Admin</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/team' ? ' active' : '' ?>" href="/team">Team</a></li>
                        <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/login' ? ' active' : '' ?>" href="/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link<?= $_SERVER['REQUEST_URI'] === '/register' ? ' active' : '' ?>" href="/register">Registrieren</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['user_id']) && !empty($headerUser)): ?>
                    <li class="nav-item ms-2">
                        <a href="/profil" class="d-inline-block" title="Mein Profil">
                            <?php
                            $avatar = $headerUser['avatar'] ?? '';
                            $isUrl = preg_match('/^https?:\/\//', $avatar);
                            $avatarSrc = $isUrl ? $avatar : ($avatar ? '/uploads/avatars/' . $avatar : '');
                            ?>
                            <?php if ($avatarSrc): ?>
                                <img src="<?= htmlspecialchars($avatarSrc) ?>" alt="Avatar" class="rounded-circle" width="32" height="32" style="object-fit:cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:1rem;">
                                    <?= htmlspecialchars((new \User())->getInitials($headerUser['name'])) ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php show_flash(); ?>