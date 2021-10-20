<?php

class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $image;
    public $ROLE;



    public function __construct($db){
        $this->conn = $db;
    }

    function read(){

        $query = "SELECT
               u.id, u.name,u.email, u.image, u.ROLE, u.createdAt
            FROM
                " . $this->table_name . " u               
            ORDER BY
                u.createdAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function readOne(){

        $query = "SELECT u.id, u.name, u.email, u.image, u.ROLE, u.createdAt
                    FROM " .$this->table_name. " u
                    WHERE u.id = :id";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->ROLE = $row['ROLE'];
        $this->image = $row['image'];


    }



    function create() {

        $query = "INSERT INTO " . $this->table_name . "
                SET
                    name = :name,
                    image = :image,
                    email = :email,
                    password = :password";


        $stmt = $this->conn->prepare($query);


        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->image=htmlspecialchars(strip_tags($this->image));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));


        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':email', $this->email);


        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);


        if($stmt->execute()) {
            return true;
        }

        return false;
    }


    function emailExists(){

        // запрос, чтобы проверить, существует ли электронная почта
        $query = "SELECT id, name, email, password ,image, ROLE 
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";


        $stmt = $this->conn->prepare( $query );
        $this->email=htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);

        $stmt->execute();

        $num = $stmt->rowCount();


        if($num>0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->ROLE = $row['ROLE'];
            $this->image = $row['image'];
            $this->password = $row['password'];

            return true;
        }


        return false;
    }





}