<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// требуется для декодирования JWT
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;


function validate_token($data){

    $jwt=isset($data) ? $data : "";

    $core = core();


    if($jwt) {


        try {
            // декодирование jwt
            $decoded = JWT::decode($jwt, $core["key"], array('HS256'));
            http_response_code(200);
            echo json_encode(array(
                "message" => "Доступ разрешен.",
                "data" => $decoded->data
            ));

        }


        catch (Exception $e){

            // код ответа
            http_response_code(401);
            echo json_encode(array(
                "message" => "Доступ закрыт.",
                "error" => $e->getMessage()
            ));
        }
    }


    else{

        // код ответа
        http_response_code(401);

        // сообщить пользователю что доступ запрещен
        echo json_encode(array("message" => "Доступ запрещён."));
    }
}