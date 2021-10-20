<?php
// показывать сообщения об ошибках
ini_set('display_errors', 1);
error_reporting(E_ALL);

// установить часовой пояс по умолчанию
date_default_timezone_set('Europe/Kiev');

function core(){
    // URL домашней страницы
    $home_url="http://192.168.0.103/";

// страница указана в параметре URL, страница по умолчанию одна
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    $targetDir = "uploads/";

// установка количества записей на странице
    $records_per_page = 5;

    // переменные, используемые для JWT
    $key = "askgo";
    $iss = "http://localhost";
    $aud = "http://localhost";
    $iat = 1356999524;
    $nbf = 1357000000;

// расчёт для запроса предела записей
    $from_record_num = ($records_per_page * $page) - $records_per_page;
    return array(
        "home_url"=>$home_url,
        "page"=>$page,
        "records_per_page"=>$records_per_page,
        "targetDir"=>$targetDir,
        "from_record_num"=>$from_record_num,
        "key"=>$key,
        "iss"=>$iss,
        "aud"=>$aud,
        "iat"=>$iat,
        "nbf"=>$nbf
    );

}