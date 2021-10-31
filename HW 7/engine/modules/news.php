<?php

/* Файл с функциями новостей */

// Функция возвращает массив всех новостей
function getNews()
{
    $sql = "SELECT * FROM `news`";
    $news = getAssocResult($sql);

    return $news;
}

// Функция возвращает запрашиваемую новость для подробного просмотра
function getNewsContent($id)
{
    $id = (int)$id;
    $sql = "SELECT * FROM `news` WHERE `id` = {$id}";
    $news = getAssocResult($sql);

    // В случае если новости нет, вернем пустое значение
    $result = [];
    if (isset($news[0]))
        $result = $news[0];

    return $result;
}