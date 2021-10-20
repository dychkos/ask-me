<?php


// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './config/database.php';
include_once './objects/answer.php';

function create($data){
    $database = new Database();
    $db = $database->getConnection();

    $answer = new Answer($db);

    if (
        !empty($data["user_id"]) &&
        !empty($data["question_id"]) &&
        !empty($data["title"])
    ) {

        $answer->user_id = $data["user_id"];
        $answer->question_id = $data["question_id"];
        $answer->title = $data["title"];


        if($answer->create()){
            http_response_code(201);
            $answer_arr = array(
                "user_id" => $answer->user_id,
                "question_id" => $answer->question_id,
                "title" => $answer->title,
            );
            echo json_encode($answer_arr, JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно создать ответ."), JSON_UNESCAPED_UNICODE);
        }
    }

    else {

        http_response_code(400);
        echo json_encode(array("message" => "Невозможно создать ответ. Данные неполные."), JSON_UNESCAPED_UNICODE);
    }
}
