<?php

declare(strict_types=1);

namespace App\services;

use Exception;
use PDOException;
use PDO;

class Db
{
    private static ?Db $instance = null;

    private $pdo;

    private function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $db   =  $_ENV['DB_NAME'];
        $user =  $_ENV['DB_USERNAME'];
        $pass =  $_ENV['DB_PASSWORD'];
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
            ;
        } catch (PDOException $e) {
            throw new Exception('Нет подключения к базе данных');
        }
    }

    private function __clone()
    {
    }

    // Основной метод доступа к экземпляру
    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
