<?php
class DatabaseConnection {
    private $host = "lt80glfe2gj8p5n2.chr7pe7iynqr.eu-west-1.rds.amazonaws.com:3306";
    private $db_name = "orlv7kb3jujoygfu";
    private $username = "unb3rnqzfdl4yzlu";
    private $password = "q06iggrwmpjf33qk";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>