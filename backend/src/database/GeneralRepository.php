<?php 
declare(strict_types = 1);
namespace Warhammerdle\Database;

use PDO;
use PDOException;
use Warhammerdle\Database\Connection;

class GeneralRepository
{
    private PDO $pdo;
    
    public function __construct()
    {
        $connection = new Connection();
        $this->pdo = $connection->getConnection();
    }

    public function simpleQuery(string $stmt): array
    {
        try {
            $query = $this->pdo->query($stmt);
            return $query->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function paramsQuery(string $stmt, array $params = []): array
    {
        try {
            $query = $this->pdo->prepare($stmt);
            $query->execute($params);
            return $query->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function insertQuery(string $table, array $data): int
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
            
            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

}
