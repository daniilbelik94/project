<?php require_once __DIR__ . '/../../core/csrf.php'; ?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-4">Benutzer bearbeiten</h2>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="/admin/edit" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rolle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="spieler" <?= $user['role'] === 'spieler' ? 'selected' : '' ?>>Spieler</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="gast" <?= $user['role'] === 'gast' ? 'selected' : '' ?>>Gast</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Speichern</button>
                    </form>
                    <hr>
                    <div class="mb-3 text-center">
                        <h5>Avatar</h5>
                        <?php if (!empty($user['avatar'])): ?>
                            <?php
                            $avatar = $user['avatar'];
                            $isUrl = preg_match('/^https?:\/\//', $avatar);
                            $avatarSrc = $isUrl ? $avatar : ($avatar ? '/uploads/avatars/' . $avatar : '');
                            ?>
                            <img src="<?= htmlspecialchars($avatarSrc) ?>" alt="Avatar" class="rounded-circle mb-2" width="64" height="64" style="object-fit:cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-2" style="width:64px;height:64px;font-size:1.5rem;">
                                <?= htmlspecialchars((new User())->getInitials($user['name'])) ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="/admin/edit-avatar" enctype="multipart/form-data" class="mt-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                            <div class="mb-2">
                                <input type="file" class="form-control form-control-sm" name="avatar_file" accept="image/png,image/jpeg,image/webp">
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-sm">Vom Computer hochladen</button>
                        </form>
                        <form method="post" action="/admin/edit-avatar-url" class="mt-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                            <div class="input-group input-group-sm mb-2">
                                <input type="url" class="form-control" name="avatar_url" placeholder="Bild-URL (jpg/png/webp)">
                                <button type="submit" class="btn btn-outline-primary">Von URL setzen</button>
                            </div>
                        </form>
                        <small class="text-muted">Erlaubt: JPG, PNG, WEBP. Max. 2MB.</small>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="/admin" class="btn btn-outline-primary">Zurück zur Übersicht</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>