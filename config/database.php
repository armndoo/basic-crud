<?php
if (!defined('INCLUDED')) {
  http_response_code(404);
  include '../status-pages/404.html';
  exit();
}
class Database{

private $host = "127.0.0.1";
private $db_name = "progetto_info";
private $user = "root";
private $password = "root";
public $conn;


public function getConnection() {
    $this->conn = null;
    try {
       $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name,$this->user,$this->password);
    } catch(PDOException $exception){
            echo "Connection error" . $exception->getMessage();
    }
        return $this->conn;
    }
}


?>
