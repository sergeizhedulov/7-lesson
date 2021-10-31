<?php

/* Файл с функциями галереи */

// Функиця возвращает массив всех имен изображений для галереи
function getImages()
{
    $sql = "SELECT * FROM `gallery` ORDER BY `likes` DESC";
    $images = getAssocResult($sql);

    return $images;
}

// Функиця возвращает имя изображения для подробного просмотра
function getFullImage($id)
{
    $id = (int)$id;
    $sql = "SELECT * FROM `gallery` WHERE `id` = {$id}";
    $image_big = getAssocResult($sql);

    // В случае если изображения нет, вернем пустое значение
    $result = [];
    if (isset($image_big[0]))
        $result = $image_big[0];

    return $result;
}

// Функция формирует запрос на добавление лайка к изображению
function addLike($id)
{
    $sql = "UPDATE `gallery` SET `likes` = `likes` + 1 WHERE `id` = {$id}";
    executeQuery($sql);
}