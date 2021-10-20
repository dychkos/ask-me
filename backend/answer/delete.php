<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './config/database.php';
include_once './objects/answer.php';

function delete($data){
    $database = new Database();
    $db = $database->getConnection();

    $answer = new Answer($db);

    $answer->id = $data;

    if ($answer->delete()) {

        http_response_code(200);

        echo json_encode(array("message" => "answer был удалён."), JSON_UNESCAPED_UNICODE);
    }


    else {
        http_response_code(503);
        echo json_encode(array("message" => "Не удалось удалить answer."));
    }
}