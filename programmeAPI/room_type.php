<?php
class RoomType{
  
    // database connection and table name
    private $conn;
    private $table_name = "room_type";
  
    // object properties

    public $room_id;
    public $programme_type_id;
      
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    function read(){
  
        // select all query
        $query = "SELECT room_id, programme_type_id name FROM  $this->table_name" ;
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }
}
?>
