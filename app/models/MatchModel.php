<?php

use Core\Model;

class MatchModel extends Model
{
    public function getUpcoming()
    {
        $stmt = self::$db->query('SELECT * FROM matches WHERE date >= CURDATE() ORDER BY date ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = self::$db->query('SELECT * FROM matches ORDER BY date DESC, id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = self::$db->prepare('SELECT * FROM matches WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateResult($id, $result)
    {
        $stmt = self::$db->prepare('UPDATE matches SET result = ? WHERE id = ?');
        $stmt->execute([$result, $id]);
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = self::$db->prepare('SELECT * FROM matches ORDER BY date DESC, id DESC LIMIT ? OFFSET ?');
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $stmt = self::$db->query('SELECT COUNT(*) as cnt FROM matches');
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    }

    public function createMatch($date, $home_team, $away_team, $result, $away_team_avatar = null)
    {
        $stmt = self::$db->prepare('INSERT INTO matches (date, home_team, away_team, result, away_team_avatar) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$date, $home_team, $away_team, $result, $away_team_avatar]);
    }
}
