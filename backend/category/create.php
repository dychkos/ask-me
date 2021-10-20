<?php


// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './config/database.php';
include_once './objects/Category.php';

function create($data){
    $database = new Database();
    $db = $database->getConnection();

    $category = new Category($db);

    if (
        !empty($data["title"])
    ) {
        $category->title = $data["title"];
        if($category->create()){
            http_response_code(201);
            echo json_encode(array("message" => "Категория была создана."), JSON_UNESCAPED_UNICODE);
        }
        else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно создать категорию."), JSON_UNESCAPED_UNICODE);
        }
    }

    else {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно создать категорию. Данные неполные."), JSON_UNESCAPED_UNICODE);
    }
}
