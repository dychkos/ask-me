<?php

class Answer
{
    private $conn;
    private $table_name="answers";

    public $id;
    public $user_id;
    public $author;
    public $author_image;
    public $title;
    public $question_id;
    public $likes_count;

    function __construct($db)
    {
        $this->conn = $db;
    }

    function read_for_question($question,$user_id){
        $query = "SELECT
                u.name as author ,u.image as author_image,a.id, a.title, a.createdAt,
                 (SELECT id FROM `ask-go`.answers_likes WHERE user_id =:user_id && answer_id=a.id)  as isLiked,
                (SELECT COUNT(answers_likes.id) FROM `ask-go`.answers_likes WHERE answers_likes.answer_id = a.id ) as likes_count
            FROM
                `ask-go`.answers a
                LEFT JOIN
                    `ask-go`.users u
                        ON a.user_id = u.id
                WHERE a.question_id =:question_id
            ORDER BY
                a.createdAt DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":question_id",$question,PDO::PARAM_INT);
        $stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;

    }

    function create(){
        $query = "INSERT INTO
               ".$this->table_name."
            SET
                user_id=:user_id, question_id=:question_id, title=:title";

        $stmt = $this->conn->prepare($query);


        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->question_id=htmlspecialchars(strip_tags($this->question_id));


        $stmt->bindParam(":user_id",$this->user_id);
        $stmt->bindParam(":title",$this->title);
        $stmt->bindParam(":question_id",$this->question_id);


        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    function like(){

        $query = "INSERT INTO
             answers_likes
            SET
                user_id=:user_id, answer_id=:answer_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id,PDO::PARAM_INT);
        $stmt->bindParam(":answer_id", $this->id,PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function unlike(){

        $query = "DELETE FROM answers_likes WHERE user_id =:user_id && answer_id =:answer_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id,PDO::PARAM_INT);
        $stmt->bindParam(":answer_id", $this->id,PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }

        return false;
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