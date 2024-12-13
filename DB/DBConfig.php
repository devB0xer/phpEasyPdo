<?php 

namespace App;

class DatabaseConfig
{
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";
    private $conn;

    public function getConnection(){
        try {
            $this->conn = new \PDO(
                "mysql:host=$this->host;dbname=$this->db_name", 
                $this->username, 
                $this->password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (\PDOException $exception) {
            echo "Ошибка соединения : " . $exception->getMessage();
            http_response_code(500);
            die;
        } 
    }
}