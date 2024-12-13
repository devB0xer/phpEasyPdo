<?php

namespace App;

class Database
{
    private $conn;
    private $tableName;

    // get connection
    public function __construct(string $table, DatabaseConfig $db = new DatabaseConfig) {
        $this->conn = $db->getConnection();
        $this->tableName = $table;
    }

    // query exec

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function get(int $id): ?array{
        $sql = "SELECT * FROM $this->tableName WHERE id = :id";
        try {
            $stmt = $this->query($sql, ['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exception) {
            echo "Ошибка при выполнении запроса: " . $exception->getMessage();
            return null;
        }
    }

    public function getAll(): ?array{
        $sql = "SELECT * FROM $this->tableName";
        try {
            $stmt = $this->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $exception) {
            echo "Ошибка при выполнении запроса: " . $exception->getMessage();
            return null;
        }
    }

    public function insert(array $data): bool{
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', :', array_keys($data));
            $sql = "INSERT INTO $this->tableName ($columns) VALUES (:$placeholders)";
            return $this->execute($sql, $data); 
        } catch (\PDOException $exception) {
            echo "Ошибка при добавлении записи: " . $exception->getMessage();
            return false;
        }
    }

    public function update(int $id, array $data): bool{
        try {
            $set = [];
            foreach ($data as $column => $value){
                $set[] = "$column = :$column";
            }
            $set = implode(', ', $set);
            $sql = "UPDATE $this->tableName SET $set WHERE id = :id";
            $data['id'] = $id;
            return $this->execute($sql, $data);
        } catch (\PDOException $exception) {
            echo "Ошибка при обновлении записи: " . $exception->getMessage();
            return false; 
        }
    }

    public function delete(int $id): bool{
        try {
            $sql = "DELETE FROM $this->tableName WHERE id = :id";
            return $this->execute($sql, ['id' => $id]);
        } catch (\PDOException $e) {
            echo "Ошибка при удалении записи: " . $e->getMessage();
            return false;
        }
    }
}