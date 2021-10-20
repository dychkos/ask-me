<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once 'config/database.php';
include_once 'objects/user.php';

function create($data)
{

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $targetDir = core()['targetDir'];
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');





    if (in_array($fileType, $allowTypes)) {

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $user->name = $data['name'];
            $user->image = $fileName;
            $user->email = $data['email'];
            $user->password = $data['password'];

            $email_exists = $user->emailExists();


            if($email_exists){
                http_response_code(400);
                echo json_encode(array("message" => "User with same email already exists"));
                return;
            }

            if (
                !empty($user->name) &&
                !empty($user->email) &&
                !empty($user->password) &&
                $user->create()
            ) {
                http_response_code(200);
                echo json_encode(array("message" => "Пользователь был создан."));
            }

        } else {
            $errorMsg = "Sorry, there was an error uploading your file.";
            http_response_code(400);
            echo json_encode(array("message" => $errorMsg));
        }
    } else {
        $errorMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
        http_response_code(400);
        echo json_encode(array("message" => $errorMsg));
    }

}