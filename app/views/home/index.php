<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php session_start(); ?>
    <?php
    $isAdmin = !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';
    $userName = $_SESSION['user_id'] ?? null;
    $userEmail = $_SESSION['email'] ?? null;
    ?>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    <div class="container py-5">
        <?php if ($isAdmin): ?>
            <h1 class="mb-4">Willkommen, Admin!</h1>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Benutzer</h5>
                            <p class="display-6">
                                <?php // TODO: вывести количество пользователей
                                echo isset($stats['users']) ? $stats['users'] : '-'; ?>
                            </p>
                            <a href="/admin" class="btn btn-outline-primary w-100">Benutzerverwaltung</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Finanzen</h5>
                            <p class="display-6">
                                <?php echo isset($stats['finances']) ? $stats['finances'] : '-'; ?>
                            </p>
                            <a href="/admin/finances" class="btn btn-outline-primary w-100">Finanzübersicht</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Spiele</h5>
                            <p class="display-6">
                                <?php echo isset($stats['matches']) ? $stats['matches'] : '-'; ?>
                            </p>
                            <a href="/admin/matches" class="btn btn-outline-primary w-100">Spiele verwalten</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="/spielplan" class="btn btn-primary me-2">Spielplan ansehen</a>
                <a href="/profil" class="btn btn-outline-primary">Mein Profil</a>
            </div>
        <?php elseif (!empty($_SESSION['user_id'])): ?>
            <h1 class="mb-4">Willkommen<?= $userName ? ', ' . htmlspecialchars($userName) : '' ?>!</h1>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Nächster Spiel</h5>
                            <p class="lead">
                                <?php echo isset($nextMatch) ? $nextMatch : 'Keine Spiele demnächst.'; ?>
                            </p>
                            <a href="/spielplan" class="btn btn-outline-primary w-100">Spielplan ansehen</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Dokumente</h5>
                            <p class="lead">
                                <?php echo isset($stats['documents']) ? $stats['documents'] : '-'; ?>
                            </p>
                            <a href="/profil" class="btn btn-outline-primary w-100">Dokumente verwalten</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <h1 class="mb-4">Willkommen beim FC Musterstadt!</h1>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Nächster Spiel</h5>
                            <p class="lead">
                                <?php echo isset($nextMatch) ? $nextMatch : 'Keine Spiele demnächst.'; ?>
                            </p>
                            <a href="/spielplan" class="btn btn-outline-primary w-100">Spielplan ansehen</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Unser Team</h5>
                            <p class="lead">Lerne unsere Mannschaft kennen!</p>
                            <a href="/team" class="btn btn-outline-primary w-100">Team ansehen</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="/login" class="btn btn-primary me-2">Login</a>
                <a href="/register" class="btn btn-outline-primary">Registrieren</a>
            </div>
        <?php endif; ?>
    </div>
    <footer class="text-center py-4 text-muted">
        &copy; 2025 FC Musterstadt
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>