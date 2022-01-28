<?php
class Room{
  
    // database connection and table name
    private $conn;
    private $table_name = "room";
  
    // object properties

    public $capacity;
    public $name;
      
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    function read(){
  
        // select all query
        $query = "SELECT capacity, name FROM  $this->table_name" ;
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }
}
?>

