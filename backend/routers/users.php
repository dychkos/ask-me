<?php

require_once "./user/read.php";
require_once "./user/read_one.php";
require_once "./user/login.php";
require_once "./user/validate_token.php";
require_once "./user/create.php";


function route($method, $urlData, $formData) {
    switch ($method){
        case "GET":{
            if (count($urlData) === 1 ){
                $user_id = $urlData[0];
                read_one($user_id);
                return;
            }
            else{
                if(isset($_GET["validate"])){
                    $jwt = $_GET["validate"];
                    validate_token($jwt);}
                else{
                    read();
                }

            }
            return;
        }
        case "POST":{
            if (count($urlData) === 1 && $urlData[0]==="login"){
                login($_POST);
            }elseif(count($urlData) === 1 && $urlData[0]==="register"){
                create($_POST);
            }
            else{
                http_response_code(404);
                echo json_encode(array("message" => "Ошибка в запросе"), JSON_UNESCAPED_UNICODE);
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