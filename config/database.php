<?php
class DatabaseConnection {
    private static $instance = null;
    private $db;

    private $host = 'localhost';
    private $dbname = 'youdemy';
    private $username = 'root';
    private $password = '';

    // Private constructor to prevent direct instantiation
    private function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Private clone method to prevent cloning of the instance
    private function __clone() {}

    // Method to get the single instance of the class
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Method to get the PDO connection
    public function getConnection() {
        return $this->db;
    }
}