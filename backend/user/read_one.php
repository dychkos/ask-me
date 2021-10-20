<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");


include_once './config/database.php';
include_once './objects/question.php';

function read_one($id){
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $user->id = $id;

    $user->readOne();

    if ($user->name!=null) {

        $user_arr = array(
            "id" =>  $user->id,
            "ROLE" =>  $user->ROLE,
            "name" => $user->name,
            "image" => $user->image,
            "email" => $user->email
        );

        http_response_code(200);
        echo json_encode($user_arr);
    }

    else {
        http_response_code(404);
        echo json_encode(array("message" => "Пользователь не существует."), JSON_UNESCAPED_UNICODE);
    }
}