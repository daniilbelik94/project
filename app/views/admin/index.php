<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Bereich</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Benutzerverwaltung</h2>
            <a href="/admin/create" class="btn btn-success">+ Benutzer hinzufügen</a>
        </div>
        <div class="mb-4">
            <input type="text" id="user-search" class="form-control" placeholder="Suche nach Name oder E-Mail...">
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white" id="users-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th></th>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th>Rolle</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody id="users-tbody">
                    <!-- Данные пользователей будут подгружаться через JS -->
                </tbody>
            </table>
        </div>
        <nav>
            <ul class="pagination justify-content-center" id="users-pagination"></ul>
        </nav>
    </div>
    <script>
        const USERS_PER_PAGE = 10;
        let currentPage = 1;
        let currentQuery = '';

        function loadUsers(page = 1, query = '') {
            fetch(`/admin/users-paginated?page=${page}&limit=${USERS_PER_PAGE}&query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('users-tbody');
                    tbody.innerHTML = '';
                    data.users.forEach(user => {
                        const initials = user.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
                        let avatarSrc = '';
                        if (user.avatar) {
                            if (/^https?:\/\//.test(user.avatar)) {
                                avatarSrc = user.avatar;
                            } else {
                                avatarSrc = '/uploads/avatars/' + user.avatar;
                            }
                        }
                        tbody.innerHTML += `<tr>
                            <td>${user.id}</td>
                            <td>
                                ${avatarSrc ?
                                    `<img src="${avatarSrc}" alt="Avatar" class="rounded-circle" width="32" height="32" style="object-fit:cover;">`
                                    : `<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:1rem;">${initials}</div>`
                                }
                            </td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>
                                <a href="/admin/edit?id=${user.id}" class="btn btn-sm btn-primary">Bearbeiten</a>
                                <a href="/admin/delete?id=${user.id}" class="btn btn-sm btn-danger" onclick="return confirm('Wirklich löschen?');">Löschen</a>
                            </td>
                        </tr>`;
                    });
                    renderPagination(data.total, page);
                });
        }

        function renderPagination(total, page) {
            const totalPages = Math.ceil(total / USERS_PER_PAGE);
            const pag = document.getElementById('users-pagination');
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

        document.getElementById('users-pagination').addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                const page = parseInt(e.target.getAttribute('data-page'));
                if (!isNaN(page) && page > 0) {
                    currentPage = page;
                    loadUsers(currentPage, currentQuery);
                }
            }
        });

        document.getElementById('user-search').addEventListener('input', function() {
            currentQuery = this.value;
            currentPage = 1;
            loadUsers(currentPage, currentQuery);
        });

        // Первая загрузка
        loadUsers();
    </script>
</body>

</html>