<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов
include_once './config/core.php';
include_once './shared/utilities.php';
include_once './config/database.php';
include_once './objects/question.php';

function read_paging(){

    $utilities = new Utilities();
    $database = new Database();
    $db = $database->getConnection();
    $question = new Question($db);

    $core = core();
    $from_record_num = $core["from_record_num"];
    $records_per_page = $core["records_per_page"];
    $page = $core["page"];
    $home_url = $core["home_url"];

    if (isset($from_record_num) && isset($records_per_page) ) {
        $stmt = $question->readPaging($from_record_num, $records_per_page);
    }else{
        http_response_code(404);
        echo json_encode(array("message" => "Ошибка в запросе"), JSON_UNESCAPED_UNICODE);
    }

    $num = $stmt->rowCount();

    if ($num>0) {

        // массив товаров
        $questions_arr=array();
        $questions_arr["records"]=array();
        $questions_arr["paging"]=array();


        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $question_item = array(
                "id" => $row["id"],
                "user_id" => $row["user_id"],
                "author" => $row["author"],
                "author_image" => $row["author_image"],
                "category" => $row["category"],
                "title"=>$row["title"],
                "description"=>$row["description"],
                "isDraft"=>$row["isDraft"],
                "answersCount"=>$row["answersCount"],
                "createdAt"=>$row["createdAt"]
            );

            array_push($questions_arr["records"], $question_item);

        }

        //пагинация
        $total_rows=$question->count();
        if (!empty($home_url) && !empty($page)) {
            $page_url="{$home_url}product/read_paging.php?";
            $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
            $questions_arr["paging"]=$paging;
        }

        //код ответа - 200 OK
        http_response_code(200);

        echo json_encode($questions_arr);
    } else {

        // код ответа - 404 Ничего не найдено
        http_response_code(404);
        echo json_encode(array("message" => "Question не найдены."), JSON_UNESCAPED_UNICODE);
    }

}