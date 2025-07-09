<?php require_once __DIR__ . '/../../core/csrf.php'; ?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="mb-4">Neue Buchung</h2>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="/admin/finances/add">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="type" class="form-label">Typ</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="Einnahme">Einnahme</option>
                                <option value="Ausgabe">Ausgabe</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Betrag (€)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Datum</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Beschreibung</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Speichern</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="/admin/finances" class="btn btn-outline-primary">Zurück zur Übersicht</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>