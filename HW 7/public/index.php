<?php

/* Точка входа в приложение */

session_start(); // Создаем файл сессии

// Подключаем файл с константами настроек
require_once $_SERVER['DOCUMENT_ROOT'] . "/../config/main.php";

$url_array = explode("/", $_SERVER['REQUEST_URI']);

// Автозагрузка дампа БД
dumpLoad();

// Чтение параметра page из url, чтобы определить, какую страницу-шаблон хочет увидеть пользователь, по умолчанию
// это будет index

$page = ""; // Записываем в переменную значение страницы
$action = ""; // Записываем в переменную значение экшена
$id = ""; // Записываем в переменную значение id

if ($url_array[1] == "") {
    $page = 'index';
} else {
    $page = $url_array[1];
    if (!$url_array[2] == "") {
        if (is_numeric($url_array[2])) {
            $id = $url_array[2];
        } else {
            $action = $url_array[2];
            if (is_numeric($url_array[3])) {
                $id = $url_array[3];
            }
        }
    }
}

$params = prepareVariables($page, $action, $id); // Записываем в переменную функцию возвращающую массив подстановок

// Вызов рендера, и передача в него имени шаблона и массива подстановок
echo render($page, $params);