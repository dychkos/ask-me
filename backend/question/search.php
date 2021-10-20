<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once './config/core.php';
include_once './config/database.php';
include_once './objects/question.php';

function read_by_search($keywords)
{

    $database = new Database();
    $db = $database->getConnection();

    $question = new Question($db);


    $stmt = $question->search($keywords);
    $num = $stmt->rowCount();

    if ($num > 0) {

        $question_arr = array();
        $question_arr["records"] = array();
        $question_item = array();


        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $question_item = array(
                "id" => $row["id"],
                "user_id" => $row["id"],
                "author" => $row["author"],
                "author_image" => $row["author_image"],
                "category" => $row["category"],
                "title" => $row["title"],
                "description" => $row["description"],
                "isDraft" => $row["isDraft"],
                "answersCount" => $row["answersCount"],
                "createdAt" => $row["createdAt"]
            );
            array_push($question_arr["records"], $question_item);
        }
        http_response_code(200);
        echo json_encode($question_arr);

    } else {
        // код ответа - 404 Ничего не найдено
        http_response_code(404);
        echo json_encode(array("message" => "q не найдены."), JSON_UNESCAPED_UNICODE);
    }
}
