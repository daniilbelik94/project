<?php

use Core\Model;

class FinanceModel extends Model
{
    public function getAll()
    {
        $stmt = self::$db->query('SELECT * FROM finances ORDER BY date DESC, id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($type, $amount, $date, $description)
    {
        $stmt = self::$db->prepare('INSERT INTO finances (type, amount, date, description) VALUES (?, ?, ?, ?)');
        $stmt->execute([$type, $amount, $date, $description]);
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = self::$db->prepare('SELECT * FROM finances ORDER BY date DESC, id DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $stmt = self::$db->query('SELECT COUNT(*) as cnt FROM finances');
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    }
}
