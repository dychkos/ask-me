<?php

require_once "./answer/read_for_question.php";
require_once "./answer/create.php";
require_once "./answer/like.php";


function route($method, $urlData, $formData) {
    switch ($method){
        case "GET":{
            if (count($urlData) === 0 && isset($_GET["q_id"]) && isset($_GET["u_id"])) {
               read_for_question($_GET["q_id"],$_GET["u_id"]);
            }else{
                http_response_code(404);
                echo json_encode(array("message" => "Ошибка в запросе"), JSON_UNESCAPED_UNICODE);
            }
            return;
        }
        case "POST":{
            if (count($urlData) === 1 && $urlData[0]==="like"){
                like($_POST,true);
            }elseif(count($urlData) === 1 && $urlData[0]==="unlike"){
                like($_POST,false);
            }
            else{
                create($formData);
            }
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

    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));

}