<?php

use Core\Model;

class DocumentModel extends Model
{
    public function create($user_id, $filename)
    {
        $stmt = self::$db->prepare('INSERT INTO documents (user_id, filename) VALUES (?, ?)');
        $stmt->execute([$user_id, $filename]);
    }

    public function getByUser($user_id, $order = 'DESC')
    {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        $stmt = self::$db->prepare('SELECT * FROM documents WHERE user_id = ? ORDER BY uploaded_at ' . $order);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
