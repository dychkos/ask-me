<?php

class Database {
    private $host = "192.168.0.103";
    private $dbname = "ask-go";
    private $username = "dychkos";
    private $pass = "root";
    public $conn;

    public function getConnection(){
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname."",$this->username,$this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        }catch (PDOException $exception){
            echo "Connection error :".$exception->getMessage();
        }

        return $this->conn;
    }
}
