<?php require_once __DIR__ . '/../../core/csrf.php'; ?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container py-5">
    <h2 class="mb-4">Mein Profil</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Persönliche Daten</h5>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>E-Mail:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Rolle:</strong> <?= htmlspecialchars($user['role']) ?></p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <div class="me-4">
                <?php if (!empty($user['avatar'])): ?>
                <img src="/uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="rounded-circle"
                    width="96" height="96" style="object-fit:cover;">
                <?php else: ?>
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                    style="width:96px;height:96px;font-size:2rem;">
                    <?= htmlspecialchars((new User())->getInitials($user['name'])) ?>
                </div>
                <?php endif; ?>
            </div>
            <div>
                <h5 class="card-title mb-3">Avatar ändern</h5>
                <?php if (!empty($_SESSION['avatar_error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['avatar_error']) ?></div>
                <?php unset($_SESSION['avatar_error']); ?>
                <?php endif; ?>
                <form method="post" action="/profil/avatar" enctype="multipart/form-data" class="mb-2">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <input type="file" class="form-control" name="avatar_file"
                            accept="image/png,image/jpeg,image/webp">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Vom Computer hochladen</button>
                </form>
                <form method="post" action="/profil/avatar-url" class="mb-2">
                    <?= csrf_field() ?>
                    <div class="input-group input-group-sm mb-2">
                        <input type="url" class="form-control" name="avatar_url" placeholder="Bild-URL (jpg/png/webp)">
                        <button type="submit" class="btn btn-outline-primary">Von URL setzen</button>
                    </div>
                </form>
                <small class="text-muted">Erlaubt: JPG, PNG, WEBP. Max. 2MB.</small>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">E-Mail ändern</h5>
                    <form method="post" action="/profil/change-email">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="new_email" class="form-label">Neue E-Mail</label>
                            <input type="email" class="form-control" id="new_email" name="new_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="current_password_email" class="form-label">Aktuelles Passwort</label>
                            <input type="password" class="form-control" id="current_password_email"
                                name="current_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">E-Mail ändern</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Passwort ändern</h5>
                    <form method="post" action="/profil/change-password">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Aktuelles Passwort</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Neues Passwort</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Neues Passwort wiederholen</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Passwort ändern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Dokumenten-Upload</h5>
            <?php
            if (!empty($_SESSION['upload_error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['upload_error']) ?></div>
            <?php unset($_SESSION['upload_error']); ?>
            <?php endif; ?>
            <form method="post" action="/profil/upload" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <input type="file" class="form-control" name="document" required>
                </div>
                <button type="submit" class="btn btn-primary">Hochladen</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Meine Dokumente </h5>
                <a href="?order=<?= $order === 'ASC' ? 'desc' : 'asc' ?>" class="btn btn-sm btn-outline-secondary">
                    Sortieren: <?= $order === 'ASC' ? 'Älteste zuerst' : 'Neueste zuerst' ?>
                </a>
            </div>
            <?php if (!empty($documents)): ?>
            <ul class="list-group">
                <?php foreach ($documents as $doc): ?>
                <?php
                        $ext = strtolower(pathinfo($doc['filename'], PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                        $isPdf = $ext === 'pdf';
                        ?>
                <li class="list-group-item d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <?php if ($isImage): ?>
                        <a href="/uploads/<?= htmlspecialchars($doc['filename']) ?>" target="_blank">
                            <img src="/uploads/<?= htmlspecialchars($doc['filename']) ?>" alt="Bild"
                                style="width:40px;height:40px;object-fit:cover;" class="rounded me-2 border">
                        </a>
                        <?php elseif ($isPdf): ?>
                        <a href="/uploads/<?= htmlspecialchars($doc['filename']) ?>" target="_blank">
                            <span class="me-2" style="font-size:2rem;vertical-align:middle;">📄</span>
                        </a>
                        <?php else: ?>
                        <a href="/uploads/<?= htmlspecialchars($doc['filename']) ?>" target="_blank">
                            <span class="me-2" style="font-size:2rem;vertical-align:middle;">📎</span>
                        </a>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($doc['filename']) ?></span>
                    </div>
                    <span
                        class="badge bg-secondary ms-2"><?= date('d.m.Y H:i', strtotime($doc['uploaded_at'])) ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p>Keine Dokumente hochgeladen.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="mt-4">
        <a href="/home" class="btn btn-outline-primary">Zurück zur Startseite</a>
    </div>
</div>
</body>

</html>