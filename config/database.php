<?php
class DatabaseConnection {
    protected $host = 'localhost';
    protected $dbname = 'youdemy';
    protected $username = 'root';
    protected $password = '';
    protected $db;

    public function connect() {
        if ($this->db === null) {
            try {
                $this->db = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                    $this->username,
                    $this->password
                );
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->db;
    }
}
