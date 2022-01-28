<?php
class Programme{
  
    // database connection and table name
    private $conn;
    private $table_name = "programme";
  
    // object properties

    public $start_time;
    public $end_time;
    public $room_id;
    public $programme_type_id;
   
    
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    function create(){
  
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    start_time=:start_time, end_time=:end_time,  room_id=:room_id, programme_type_id=:programme_type_id";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->start_time=htmlspecialchars(strip_tags($this->start_time));
        $this->end_time=htmlspecialchars(strip_tags($this->end_time));
        $this->room_id=htmlspecialchars(strip_tags($this->room_id));
        $this->programme_type_id=htmlspecialchars(strip_tags($this->programme_type_id));
        
      
        // bind values
        $stmt->bindParam(":start_time", $this->start_time);
        $stmt->bindParam(":end_time", $this->end_time);
        $stmt->bindParam(":room_id", $this->d);
        $stmt->bindParam(":programme_type_id", $this->category_id);
        
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;

        
          
    }
    function read(){
  
        // select all query
        $query = "SELECT start_time, end_time FROM  $this->table_name" ;
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }

    // delete the programme
function delete(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
}
?>
