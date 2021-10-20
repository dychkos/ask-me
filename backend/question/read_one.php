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

    $question = new Question($db);
    $question->id = $id;

    $question->readOne();

    if ($question->title!=null) {

        $question_arr = array(
            "id" =>  $question->id,
            "user_id" => $question->user_id,
            "category_id" => $question->category_id,
            "author" => $question->author,
            "author_image" => $question->author_image,
            "title" => $question->title,
            "answersCount"=>$question->answersCount,
            "description" => $question->description,
            "isDraft" => $question->isDraft
        );


        http_response_code(200);
        echo json_encode($question_arr);
    }

    else {
        http_response_code(404);
        echo json_encode(array("message" => "Вопрос не существует."), JSON_UNESCAPED_UNICODE);
    }
}