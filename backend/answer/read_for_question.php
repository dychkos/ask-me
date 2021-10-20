<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once './config/database.php';
include_once './objects/answer.php';

function read_for_question($question_id,$user_id) {

    $database = new Database();
    $db = $database->getConnection();

    $answer = new Answer($db);
    $stmt = $answer->read_for_question($question_id,$user_id);
    $num = $stmt->rowCount();

    if($num > 0){

        $answer_arr = array();
        $answer_arr["records"]  = array();
        $answer_item = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $answer_item = array(
                "id" => $row["id"],
                "author" => $row["author"],
                "isLiked" => $row["isLiked"],
                "author_image" => $row["author_image"],
                "title"=>$row["title"],
                "likes_count"=>$row["likes_count"],
                "createdAt"=>$row["createdAt"]
            );
            array_push($answer_arr["records"], $answer_item);
        }
        http_response_code(200);
        echo json_encode($answer_arr);

    }else {
        http_response_code(404);
        echo json_encode(array("message" => "Ответы не найдены."), JSON_UNESCAPED_UNICODE);
    }

}