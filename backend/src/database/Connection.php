<?php
declare(strict_types = 1);
namespace Warhammedle\Database;

use PDO;
use PDOException;

class Connection
{
    private PDO $pdo;
    public function __construct()
    {
        $host = $_ENV["DB_HOST"];
        $dbName = $_ENV["DB_NAME"];
        $user = $_ENV["DB_USER"];
        $password = $_ENV["DB_PASS"];

        try {
            $this->pdo = new PDO(
                "mysql:host={$host};dbname={$dbName};charset=utf8",
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
