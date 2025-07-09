<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container py-5">
    <h2 class="mb-4 text-center">Kommende Spiele</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-primary">
                <tr>
                    <th>Datum</th>
                    <th>Heim</th>
                    <th>Gast</th>
                    <th>Ergebnis</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($matches)): ?>
                <?php foreach ($matches as $match): ?>
                <tr>
                    <td><?= htmlspecialchars(date('d.m.Y', strtotime($match['date']))) ?></td>
                    <td class="d-flex align-items-center">
                        <img src="https://media.teamfanapp.com/clubs/1/logo.png" alt="FC Musterstadt"
                            class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                        <span><?= htmlspecialchars($match['home_team']) ?></span>
                    </td>
                    <td class="d-flex align-items-center">
                        <?php
                                $avatar = $match['away_team_avatar'] ?? '';
                                $isUrl = preg_match('/^https?:\/\//', $avatar);
                                $avatarSrc = $isUrl ? $avatar : ($avatar ? '/uploads/avatars/' . $avatar : '');
                                ?>

                    </td>
                    <td>

                        <?php if ($avatarSrc): ?>
                        <img src="<?= htmlspecialchars($avatarSrc) ?>"
                            alt="<?= htmlspecialchars($match['away_team']) ?>" class="rounded-circle me-2" width="32"
                            height="32" style="object-fit:cover;">
                        <?php else:
                                    $initials = mb_strtoupper(mb_substr(preg_replace('/[^\pL\d]+/u', '', $match['away_team']), 0, 2)); ?>
                        <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center me-2"
                            style="width:32px;height:32px;font-size:1rem;">
                            <?= $initials ?>
                        </div>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($match['away_team']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($match['result'] ?? '-') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Keine Spiele gefunden.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4">
        <a href="/home" class="btn btn-outline-primary">Zurück zur Startseite</a>
    </div>
</div>
</body>

</html>