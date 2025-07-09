<?php require_once __DIR__ . '/../../core/csrf.php'; ?>
<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Spiele verwalten</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMatchModal">+ Neues Spiel</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-primary">
                <tr>
                    <th>Datum</th>
                    <th>Heim</th>
                    <th>Gast</th>
                    <th>Ergebnis</th>
                    <th>Aktion</th>
                </tr>
            </thead>
            <tbody id="matches-tbody">
                <!-- Данные матчей будут подгружаться через JS -->
            </tbody>
        </table>
    </div>
    <nav>
        <ul class="pagination justify-content-center" id="matches-pagination"></ul>
    </nav>
</div>
<!-- Модальное окно для добавления матча -->
<div class="modal fade" id="addMatchModal" tabindex="-1" aria-labelledby="addMatchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/admin/matches/add" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMatchModalLabel">Neues Spiel hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="date" class="form-label">Datum</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="away_team" class="form-label">Gegner</label>
                        <input type="text" class="form-control" id="away_team" name="away_team" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo des Gegners</label>
                        <input type="file" class="form-control mb-2" name="away_team_avatar_file" accept="image/png,image/jpeg,image/webp">
                        <input type="url" class="form-control" name="away_team_avatar_url" placeholder="Bild-URL (jpg/png/webp)">
                        <small class="text-muted">Optional. JPG, PNG, WEBP. Max. 2MB.</small>
                    </div>
                    <div class="mb-3">
                        <label for="result" class="form-label">Ergebnis</label>
                        <input type="text" class="form-control" id="result" name="result" placeholder="z.B. 2:1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const MATCHES_PER_PAGE = 10;
    let currentMatchPage = 1;

    function loadMatches(page = 1) {
        fetch(`/admin/matches-paginated?page=${page}&limit=${MATCHES_PER_PAGE}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('matches-tbody');
                tbody.innerHTML = '';
                data.matches.forEach(m => {
                    // Лого и инициалы для домашней команды
                    let homeLogo = `<img src=\"https://media.teamfanapp.com/clubs/1/logo.png\" alt=\"FC Musterstadt\" class=\"rounded-circle me-2\" width=\"32\" height=\"32\" style=\"object-fit:cover;\">`;
                    let homeName = m.home_team;
                    // Лого или инициалы для гостевой команды
                    let awayLogo = '';
                    if (m.away_team_avatar) {
                        if (/^https?:\/\//.test(m.away_team_avatar)) {
                            awayLogo = `<img src=\"${m.away_team_avatar}\" alt=\"${m.away_team}\" class=\"rounded-circle me-2\" width=\"32\" height=\"32\" style=\"object-fit:cover;\">`;
                        } else {
                            awayLogo = `<img src=\"/uploads/avatars/${m.away_team_avatar}\" alt=\"${m.away_team}\" class=\"rounded-circle me-2\" width=\"32\" height=\"32\" style=\"object-fit:cover;\">`;
                        }
                    } else {
                        const initials = m.away_team.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
                        awayLogo = `<div class=\"rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center me-2\" style=\"width:32px;height:32px;font-size:1rem;\">${initials}</div>`;
                    }
                    tbody.innerHTML += `<tr>
                        <td>${new Date(m.date).toLocaleDateString('de-DE')}</td>
                        <td class=\"d-flex align-items-center\">${homeLogo}<span>${homeName}</span></td>
                        <td class=\"d-flex align-items-center\">${awayLogo}<span>${m.away_team}</span></td>
                        <td>${m.result ? m.result : '-'}</td>
                        <td><a href=\"/admin/matches/edit?id=${m.id}\" class=\"btn btn-sm btn-primary\">Ergebnis bearbeiten</a></td>
                    </tr>`;
                });
                renderMatchPagination(data.total, page);
            });
    }

    function renderMatchPagination(total, page) {
        const totalPages = Math.ceil(total / MATCHES_PER_PAGE);
        const pag = document.getElementById('matches-pagination');
        pag.innerHTML = '';
        if (totalPages <= 1) return;
        let prevDisabled = page === 1 ? 'disabled' : '';
        let nextDisabled = page === totalPages ? 'disabled' : '';
        pag.innerHTML += `<li class="page-item ${prevDisabled}"><a class="page-link" href="#" data-page="${page-1}">«</a></li>`;
        for (let i = 1; i <= totalPages; i++) {
            let active = i === page ? 'active' : '';
            pag.innerHTML += `<li class="page-item ${active}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }
        pag.innerHTML += `<li class="page-item ${nextDisabled}"><a class="page-link" href="#" data-page="${page+1}">»</a></li>`;
    }
    document.getElementById('matches-pagination').addEventListener('click', function(e) {
        if (e.target.tagName === 'A') {
            e.preventDefault();
            const page = parseInt(e.target.getAttribute('data-page'));
            if (!isNaN(page) && page > 0) {
                currentMatchPage = page;
                loadMatches(currentMatchPage);
            }
        }
    });
    // Первая загрузка
    loadMatches();
</script>
</body>

</html>