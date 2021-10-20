<?php

require_once "./question/read.php";
require_once "./question/read_one.php";
require_once "./question/read_category.php";
require_once "./question/read_for_user.php";
require_once "./question/search.php";
require_once "./question/create.php";
require_once "./question/delete.php";
require_once "./question/read_paging.php";
require_once "./question/change_status.php";

function route($method, $urlData, $formData) {
    switch ($method){
        case "GET":{
            if (count($urlData) === 0) {
                if(isset($_GET["page"])){
                    read_paging();
                }
                elseif(isset($_GET["search"])){
                    $keywords = $_GET["search"];
                    read_by_search($keywords);
                }elseif(isset($_GET["category"])){
                    $category = $_GET["category"];
                    read_category($category);
                }elseif(isset($_GET["user"])){
                    $user = $_GET["user"];
                    read_for_user($user);
                }
                else{
                    read();
                }
                return;
            }elseif (count($urlData) === 1 ){
                $question_id = $urlData[0];
                read_one($question_id);
                return;
            }else{
                http_response_code(404);
                echo json_encode(array("message" => "Ошибка в запросе"), JSON_UNESCAPED_UNICODE);
            }
            return;
        }
        case "POST":{
            if (isset($_POST["make_public"])){
                change_status($_POST["make_public"]);
                return;
            }
            create($formData);
            return;
        }
        case "DELETE":{
            if(count($urlData)===1){
                delete($urlData[0]);
            }else{
                http_response_code(404);
                echo json_encode(array("message" => "Ошибка в запросе"), JSON_UNESCAPED_UNICODE);
            }
            return;

        }
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));

}