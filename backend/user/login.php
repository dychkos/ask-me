<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



include_once 'config/database.php';
include_once 'objects/user.php';

// подключение файлов jwt
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

function login ($data){

    $core = core();

    $database = new Database();
    $db = $database->getConnection();


    $user = new User($db);



    $user->email = $data['email'];
    $email_exists = $user->emailExists();


    if ( $email_exists && password_verify($data['password'], $user->password) ) {

        $token = array(
            "iss" => $core["iss"],
            "aud" => $core["aud"],
            "iat" => $core["iat"],
            "nbf" => $core["nbf"],
            "data" => array(
                "id" => $user->id,
                "name" => $user->name,
                "image" => $user->image,
                "email" => $user->email,
                "ROLE" => $user->ROLE
            )
        );


        http_response_code(200);

        // создание jwt
        $jwt = JWT::encode($token, $core["key"]);
        echo json_encode(
            array(
                "message" => "Успешный вход в систему.",
                "user"=>$token['data'],
                "jwt" => $jwt
            )
        );

    }

// Если электронная почта не существует или пароль не совпадает,
// сообщим пользователю, что он не может войти в систему
    else {

        // код ответа
        http_response_code(401);

        // сказать пользователю что войти не удалось
        echo json_encode(array("message" => "Failed to login"));
    }

}

