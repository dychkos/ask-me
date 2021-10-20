<?php

require_once "./category/read.php";
require_once "./category/create.php";
require_once "./category/delete.php";


function route($method, $urlData, $formData) {
    switch ($method){
        case "GET":{
            read();
            return;
        }
        case "POST":{
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

    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));

}