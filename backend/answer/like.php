<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './config/database.php';
include_once './objects/answer.php';

function like($data,$like){
    $database = new Database();
    $db = $database->getConnection();

    $answer = new Answer($db);


    $answer->id = $data["answer_id"];
    $answer->user_id = $data["user_id"];

    if($like){
        if ($answer->like()) {
            http_response_code(200);
            echo json_encode(array("message" => "Количество лайков увеличенно"), JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Не удалось увеличить количество лайков"));
        }
    }else{
        if ($answer->unlike()) {
            http_response_code(200);
            echo json_encode(array("message" => "Лайк убран"), JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Не удалось убрать лайк"));
        }
    }


}