<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzübersicht</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Finanzübersicht</h2>
            <a href="/admin/finances/add" class="btn btn-success">+ Neue Buchung</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-primary">
                    <tr>
                        <th>Datum</th>
                        <th>Typ</th>
                        <th>Betrag (€)</th>
                        <th>Beschreibung</th>
                    </tr>
                </thead>
                <tbody id="finances-tbody">
                    <!-- Данные финансов будут подгружаться через JS -->
                </tbody>
            </table>
        </div>
        <nav>
            <ul class="pagination justify-content-center" id="finances-pagination"></ul>
        </nav>
    </div>
    <script>
        const FINANCES_PER_PAGE = 10;
        let currentFinancePage = 1;

        function loadFinances(page = 1) {
            fetch(`/admin/finances-paginated?page=${page}&limit=${FINANCES_PER_PAGE}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('finances-tbody');
                    tbody.innerHTML = '';
                    data.finances.forEach(f => {
                        tbody.innerHTML += `<tr>
                        <td>${new Date(f.date).toLocaleDateString('de-DE')}</td>
                        <td>${f.type}</td>
                        <td>${parseFloat(f.amount).toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td>${f.description}</td>
                    </tr>`;
                    });
                    renderFinancePagination(data.total, page);
                });
        }

        function renderFinancePagination(total, page) {
            const totalPages = Math.ceil(total / FINANCES_PER_PAGE);
            const pag = document.getElementById('finances-pagination');
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
        document.getElementById('finances-pagination').addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                const page = parseInt(e.target.getAttribute('data-page'));
                if (!isNaN(page) && page > 0) {
                    currentFinancePage = page;
                    loadFinances(currentFinancePage);
                }
            }
        });
        // Первая загрузка
        loadFinances();
    </script>
</body>

</html>