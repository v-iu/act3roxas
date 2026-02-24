<?php
// config/Database.php

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = include __DIR__ . '/config.php';
        // build DSN, include port if provided
        $dsn = "mysql:host=" . $config['db_host'];
        if (!empty($config['db_port'])) {
            $dsn .= ";port=" . $config['db_port'];
        }
        $dsn .= ";dbname=" . $config['db_name'] . ";charset=" . $config['db_charset'];

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $this->pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
