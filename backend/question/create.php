<?php


// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

include_once './config/database.php';
include_once './objects/question.php';

function create($data){
    $database = new Database();
    $db = $database->getConnection();

    $question = new Question($db);

    if (
        !empty($data["user_id"]) &&
        !empty($data["category_id"]) &&
        !empty($data["title"]) &&
        !empty($data["description"]) &&
        isset($data["isDraft"])

    ) {

        $question->user_id = $data["user_id"];
        $question->category_id = $data["category_id"];
        $question->title = $data["title"];
        $question->description = $data["description"];
        $question->isDraft = $data["isDraft"];


        if($question->create()){
            http_response_code(201);
            $question_arr = array(
                "id" =>  $question->id,
                "user_id" => $question->user_id,
                "category_id" => $question->category_id,
                "title" => $question->title,
                "answersCount"=>$question->answersCount,
                "description" => $question->description
            );
            echo json_encode(array($question_arr), JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно создать вопрос."), JSON_UNESCAPED_UNICODE);
        }
    }

    else {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно создать вопрос. Данные неполные."), JSON_UNESCAPED_UNICODE);
    }
}
