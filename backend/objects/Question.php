<?php

class Question
{
    private $conn;
    private $table_name="questions";

    public $id;
    public $user_id;
    public $author;
    public $author_image;
    public $title;
    public $description;
    public $category_id;
    public $isDraft;
    public $answersCount;

    function __construct($db)
    {
        $this->conn = $db;
    }

    function read(){
        $query = "SELECT
                c.title as category, u.name as author, u.image as author_image, q.user_id, q.id, q.title, q.description, q.isDraft, q.createdAt,
                (SELECT COUNT(answers.id) FROM `ask-go`.answers WHERE answers.question_id = q.id ) as answersCount
            FROM
                `ask-go`.".$this->table_name." q
                LEFT JOIN
                    categories c                    
                        ON q.category_id = c.id
                LEFT JOIN
                    users u
                        ON q.user_id = u.id
                WHERE q.isDraft !=1
            ORDER BY
                q.createdAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;

    }

    function read_category($category){
        $query = "SELECT
                c.title as category, u.name as author, u.image as author_image, q.user_id, q.id, q.title, q.description, q.isDraft, q.createdAt,
                (SELECT COUNT(answers.id) FROM `ask-go`.answers WHERE answers.question_id = q.id ) as answersCount
            FROM
                `ask-go`.".$this->table_name." q
                LEFT JOIN
                    categories c
                        ON q.category_id = c.id
                 LEFT JOIN
                    users u
                        ON q.user_id = u.id
                WHERE c.title = :category AND q.isDraft !=1
            ORDER BY
                q.createdAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category",$category);
        $stmt->execute();

        return $stmt;

    }

    function read_for_user($user){
        $query = "SELECT
                c.title as category, u.name as author, u.image as author_image, q.user_id, q.id, q.title, q.description, q.isDraft, q.createdAt,
                (SELECT COUNT(answers.id) FROM `ask-go`.answers WHERE answers.question_id = q.id ) as answersCount
            FROM
                `ask-go`.".$this->table_name." q
                LEFT JOIN
                    categories c
                        ON q.category_id = c.id
                 LEFT JOIN
                    users u
                        ON q.user_id = u.id
                WHERE q.user_id = :user_id
            ORDER BY
                q.createdAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id",$user);
        $stmt->execute();

        return $stmt;

    }

    function create(){
        $query = "INSERT INTO
               ".$this->table_name."
            SET
                user_id=:user_id, title=:title, description=:description,category_id=:category_id,isDraft=:isDraft";
        $stmt = $this->conn->prepare($query);



        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
       // $this->isDraft=htmlspecialchars(strip_tags($this->isDraft));

        $stmt->bindParam(":user_id",$this->user_id);
        $stmt->bindParam(":title",$this->title);
        $stmt->bindParam(":description",$this->description);
        $stmt->bindParam(":category_id",$this->category_id);
        $stmt->bindParam(":isDraft",$this->isDraft);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function readOne(){
        $query = "SELECT c.title as category_name, 
       (SELECT COUNT(answers.id) FROM answers WHERE answers.question_id = :id ) as answersCount,
       q.id, q.user_id ,u.name as author, u.image as author_image, q.title, q.description, q.category_id, q.isDraft , q.createdAt 
        FROM questions q 
            LEFT JOIN categories c ON q.category_id = c.id 
            LEFT JOIN users u ON q.user_id = u.id
            WHERE q.id = :id LIMIT 0,1 ";


        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->answersCount = $row['answersCount'];
        $this->description = $row['description'];
        $this->author = $row['author'];
        $this->author_image = $row['author_image'];
        $this->isDraft = $row['isDraft'];
        $this->category_id = $row['category_id'];
        $this->user_id = $row['user_id'];
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

    function change_status(){
        $query = "UPDATE ". $this->table_name ." SET isDraft = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id,PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function search($keywords){

        $query = "SELECT
                c.title as category, u.name as author, u.image as author_image, q.user_id, q.id, q.title, q.description, q.isDraft, q.createdAt,
                (SELECT COUNT(answers.id) FROM answers WHERE answers.question_id = q.id ) as answersCount
            FROM
                " . $this->table_name . " q
                LEFT JOIN
                    categories c
                        ON q.category_id = c.id
                 LEFT JOIN
                    users u
                        ON q.user_id = u.id
            WHERE
                q.title LIKE ? OR q.description LIKE ? OR c.title LIKE ? AND q.isDraft !=1
            ORDER BY
                q.createdAt DESC";


        $stmt = $this->conn->prepare($query);
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();

        return $stmt;
    }

    public function readPaging($from_record_num, $records_per_page){

        $query = "SELECT
              c.title as category, u.name as author, u.image as author_image, q.user_id, q.id, q.title, q.description, q.isDraft, q.createdAt,
                (SELECT COUNT(answers.id) FROM answers WHERE answers.question_id = q.id ) as answersCount
            FROM
                " . $this->table_name . " q
                LEFT JOIN
                    categories c
                        ON q.category_id = c.id
                LEFT JOIN
                    users u
                        ON q.user_id = u.id
                WHERE q.isDraft !=1
            ORDER BY q.createdAt DESC
            LIMIT ?, ?";

        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    public function count(){
        $query = "SELECT COUNT(*)  as total_rows FROM " . $this->table_name;

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

}