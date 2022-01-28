<?php
class Attendees{
  
    // database connection and table name
    private $conn;
    private $table_name = "attendees";
  
    // object properties

    public $cnp;
    public $programme_id;
      
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    function read(){
  
        // select all query
        $query = "SELECT cnp,programme_id  FROM  $this->table_name" ;
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }
    function countAttendees(){
  
        // select all query
        $query = "SELECT count(*) as total from". $this->table_name." where programme_id= ?" ;
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->programme_id));
  
    
        $stmt->bindParam(1, $this->programme_id);
      
        // execute query
        $stmt->execute();
      
        return $stmt;
    }
    function create(){
  
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    cnp=:cnp, programme_id=:programme_id";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->cnp=htmlspecialchars(strip_tags($this->cnp));
        $this->programme_id=htmlspecialchars(strip_tags($this->programme_id));
        
        
      
        // bind values
        $stmt->bindParam(":cnp", $this->cnp);
        $stmt->bindParam(":programme_id", $this->programme_id);
        
        
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;

        
          
    }
}
?>
