<?php

use Core\Model;

class User extends Model
{
    public function create($name, $email, $password_hash, $role)
    {
        $stmt = self::$db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $password_hash, $role]);
    }

    public function findByEmail($email)
    {
        $stmt = self::$db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = self::$db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = self::$db->query('SELECT * FROM users ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $email, $role)
    {
        $stmt = self::$db->prepare('UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?');
        $stmt->execute([$name, $email, $role, $id]);
    }

    public function delete($id)
    {
        $stmt = self::$db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function search($query)
    {
        if (!$query) {
            return $this->getAll();
        }
        $stmt = self::$db->prepare('SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC');
        $like = "%$query%";
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = self::$db->prepare('SELECT * FROM users ORDER BY id DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $stmt = self::$db->query('SELECT COUNT(*) as cnt FROM users');
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    }

    public function searchPaginated($query, $limit, $offset)
    {
        if (!$query) {
            return $this->getPaginated($limit, $offset);
        }
        $stmt = self::$db->prepare('SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?');
        $like = "%$query%";
        $stmt->bindValue(1, $like, PDO::PARAM_STR);
        $stmt->bindValue(2, $like, PDO::PARAM_STR);
        $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(4, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSearch($query)
    {
        if (!$query) return $this->countAll();
        $stmt = self::$db->prepare('SELECT COUNT(*) as cnt FROM users WHERE name LIKE ? OR email LIKE ?');
        $like = "%$query%";
        $stmt->execute([$like, $like]);
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    }

    public function updateAvatar($id, $avatarPath)
    {
        $stmt = self::$db->prepare('UPDATE users SET avatar = ? WHERE id = ?');
        $stmt->execute([$avatarPath, $id]);
    }

    public function getInitials($name)
    {
        $parts = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach ($parts as $p) {
            if ($p) $initials .= mb_substr($p, 0, 1);
        }
        return mb_strtoupper(mb_substr($initials, 0, 2));
    }

    public function getAllByRole($role)
    {
        $stmt = self::$db->prepare('SELECT * FROM users WHERE role = ? ORDER BY id ASC');
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDb()
    {
        if (!self::$db) {
            new self(); // вызовет конструктор и инициализирует self::$db
        }
        return self::$db;
    }
}
