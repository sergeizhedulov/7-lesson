<?php

/* Файл с функциями базы данных */

// Функция автоматически загружает дамп БД из указанного файла
function dumpLoad()
{
    $result = mysqli_query(getDb(), "SHOW TABLES FROM " . DB . ";");

    if (mysqli_num_rows($result) === 0) {
        $dump = file_get_contents("../data/shop.sql");
        $a = 0;
        while ($b = strpos($dump, ";", $a + 1)) {
            $a = substr($dump, $a + 1, $b - $a);
            mysqli_query(getDb(), $a);
            $a = $b;
        }

        var_dump("Дамп загружен!");
    }
}

/*
 * Функция, осуществляющая соединение с базой данных и возвращающая его
 * static, позволяет сохранить состояние и вернуть уже текущее соединение
 * чтобы не делать нового
 */
function getDb()
{
    static $db = null;

    if (is_null($db)) {
        $db = @mysqli_connect(HOST, USER, PASS, DB) or die("Could not connect: " . mysqli_connect_error());
    }

    return $db;
}

// При желании можно закрыть соединение (если уж и вызывать, то после render на главной)
function closeDb()
{
    mysqli_close(getDb());
}

/*
 * Обертка для выполнения любого запроса
 * Передаем в параметре текст sql-запроса
 * Возвращаем результат, в основном использовать для
 * операций update и delete, которые не требуют возврата данных
 */
function executeQuery($sql)
{
    $db = getDb();
    $result = @mysqli_query($db, $sql) or die(mysqli_error($db));

    return $result;
}

/*
 * Обертка для выполнения запроса на получение данных
 * Данные возвращаются в виде ассоциативного массива
 * Цикл по получению данных уже реализован в этой функции
 */
function getAssocResult($sql)
{
    $db = getDb();
    $result = @mysqli_query($db, $sql) or die(mysqli_error($db));
    $array_result = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $array_result[] = $row;
    }

    return $array_result;
}