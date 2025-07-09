<?php

namespace Core;

use PDO;

class Model
{
    protected static $db;

    public function __construct()
    {
        if (!self::$db) {
            $config = require __DIR__ . '/../../config/database.php';
            self::$db = new PDO($config['dsn'], $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
    }
}