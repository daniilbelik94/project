<?php require_once __DIR__ . '/../../core/csrf.php'; ?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-4">Ergebnis bearbeiten</h2>
                    <form method="post" action="/admin/matches/edit">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($match['id']) ?>">
                        <div class="mb-3">
                            <label class="form-label">Spiel</label>
                            <div class="form-control-plaintext">
                                <?= htmlspecialchars($match['home_team']) ?> vs. <?= htmlspecialchars($match['away_team']) ?> (<?= htmlspecialchars(date('d.m.Y', strtotime($match['date']))) ?>)
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="result" class="form-label">Ergebnis</label>
                            <input type="text" class="form-control" id="result" name="result" value="<?= htmlspecialchars($match['result'] ?? '') ?>" placeholder="z.B. 2:1" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Speichern</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="/admin/matches" class="btn btn-outline-primary">Zurück zur Übersicht</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>