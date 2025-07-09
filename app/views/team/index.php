<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-3 text-center">
            <img src="<?= htmlspecialchars($clubInfo['logo']) ?>" alt="Logo" class="rounded-circle mb-3" width="120" height="120" style="object-fit:cover;">
        </div>
        <div class="col-md-9">
            <h1 class="fw-bold mb-2"><?= htmlspecialchars($clubInfo['name']) ?></h1>
            <p class="mb-1 text-muted">Gegründet: <?= htmlspecialchars($clubInfo['founded']) ?></p>
            <p><?= htmlspecialchars($clubInfo['description']) ?></p>
        </div>
    </div>
    <h2 class="mb-4">Mannschaft</h2>
    <div class="row g-4">
        <?php foreach ($players as $player): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <?php
                        $avatar = $player['avatar'] ?? '';
                        $isUrl = preg_match('/^https?:\/\//', $avatar);
                        $avatarSrc = $isUrl ? $avatar : ($avatar ? '/uploads/avatars/' . $avatar : '');
                        ?>
                        <?php if ($avatarSrc): ?>
                            <img src="<?= htmlspecialchars($avatarSrc) ?>" alt="Avatar" class="rounded-circle mb-2" width="64" height="64" style="object-fit:cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mb-2 mx-auto" style="width:64px;height:64px;font-size:1.5rem;">
                                <?= htmlspecialchars((new \User())->getInitials($player['name'])) ?>
                            </div>
                        <?php endif; ?>
                        <h5 class="card-title mb-0"><?= htmlspecialchars($player['name']) ?></h5>
                        <p class="text-muted small mb-0">Spieler</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>