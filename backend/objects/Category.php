<?php

class Category
{
    private $conn;
    private $table_name="categories";

    public $id;
    public $title;

    function __construct($db)
    {
        $this->conn = $db;
    }

    function read(){
        $query = "SELECT
                     c.id, c.title             
            FROM
                `ask-go`.".$this->table_name." c";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;

    }

    function create(){
        $query = "INSERT INTO
               ".$this->table_name."
            SET
                title=:title";
        $stmt = $this->conn->prepare($query);

        $this->title=htmlspecialchars(strip_tags($this->title));

        $stmt->bindParam(":title",$this->title);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


}