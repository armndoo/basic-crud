<?php
if (!defined('INCLUDED')) {
  http_response_code(404);
  include '../status-pages/404.html';
  exit();
}
class Category{
    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id,name FROM {$this->table_name} ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    function readName() {
        $query = "SELECT name FROM {$this->table_name} WHERE id = {$this->id} limit 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
                $this->name = $row['name'];
            } else {
                $this->name = "Nessuna categoria";
            }
    }
}
?>
