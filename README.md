# Vereinsmanagement-System FC Musterstadt

Webbasiertes Vereinsmanagement-System für den Fußballverein "FC Musterstadt".

## Struktur

- `app/` — MVC (Controller, Model, View, Core)
- `public/` — Webroot (index.php, css, js, uploads)
- `config/` — Конфиги
- `vendor/` — Composer (опционально)

## Datenbank: Tabelle users

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','spieler','gast') NOT NULL DEFAULT 'spieler',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Datenbank: Tabelle matches

```sql
CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    home_team VARCHAR(100) NOT NULL,
    away_team VARCHAR(100) NOT NULL,
    result VARCHAR(20) DEFAULT NULL
);
```

## Datenbank: Tabelle documents

```sql
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Beispiel: 20 Test-Spieler einfügen

```sql
INSERT INTO users (name, email, password_hash, role) VALUES
('Spieler 1', 'player1@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 2', 'player2@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 3', 'player3@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 4', 'player4@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 5', 'player5@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 6', 'player6@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 7', 'player7@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 8', 'player8@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 9', 'player9@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 10', 'player10@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 11', 'player11@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 12', 'player12@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 13', 'player13@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 14', 'player14@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 15', 'player15@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 16', 'player16@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 17', 'player17@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 18', 'player18@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 19', 'player19@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler'),
('Spieler 20', 'player20@fc-musterstadt.de', '$2y$10$wH1Qw1Qw1Qw1Qw1Qw1Qw1u1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'spieler');
```

Пароль для всех: spieler123
