<?php
class Database
{ //database connection details
    private $host = "127.0.0.1:3306";
    private $db_name = "programmeDB";
    private $username = "root";
    private $password = "1122";
    public $conn;


    public function getConnection()
    {
        echo "Starting connection...";

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        echo "Connected succesfully! ";
        echo "\n";
        return $this->conn;
    }
}
