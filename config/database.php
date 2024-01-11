<?php 

class database {
    private $host="127.0.0.1:3307";
    private $username="root";
    private $password="";
    private $database="taskify";
    public $conn;

    public function connection() {
        $this->conn = new mysqli($this->host, $this->username , $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function query($sql) {
        $result = $this->conn->query($sql);
        return $result;
    }
}

$db = new database();
$db -> connection();

?>
