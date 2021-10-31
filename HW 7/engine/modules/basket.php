<?php

/* Файл с функциями корзины */

// Функция возвращает массив товаров для текущей сессии
function getBasket()
{
    $session_id = session_id(); // Записываем в переменную id сессии
    $sql = "SELECT basket.id as basket_id, goods.id as good_id, goods.name as name, goods.price as price, goods.image as image FROM `basket`, `goods` WHERE basket.goods_id = goods.id AND `session_id` = '{$session_id}'";
    // Записываем в переменную SQL запрос на поиск данных из объединенных таблиц для текущей сессии
    // Таблица basket
    // Поле //
    // id - Псевдоним basket_id
    // Таблица goods
    // Поле //
    // id - Псевдоним good_id
    // name - Псевдоним name
    // price - Псевдоним price
    // image - Псевдоним image
    $basket = getAssocResult($sql); // Записываем в переменную полученный в результате выполнения функции
    // ассоциативный массив

    return $basket; // Возвращаем ассоциативный массив
}

// Функция возвращает стоимость всех товаров для id сессии
function totalCost()
{
    $session_id = session_id(); // Записываем в переменную id сессии
    $sql = "SELECT SUM(goods.price) as totalCost FROM `basket`, `goods` WHERE basket.goods_id = goods.id AND `session_id` = '{$session_id}'";
    // Записываем в переменную SQL запрос на подсчет суммы товаров в корзине для текущей сессии

    return getAssocResult($sql)[0]['totalCost']; // Возвращаем полученное значение из двумерного массива полученного
    // в результате выполнения функции
}

// Функция возвращает количество товаров для id сессии
function getBasketCount()
{
    $session_id = session_id(); // Записываем в переменную id сессии
    $sql = "SELECT COUNT(*) as count FROM `basket` WHERE `session_id`='$session_id'"; // Записываем в переменную SQL
    // запрос на подсчет количества товаров в корзине для текущей сессии

    return getAssocResult($sql)[0]['count']; // Возвращаем полученное значение из двумерного массива полученного
    // в результате выполнения функции
}

// Функция помещает товар с id = $id в корзину для текущей сессии
function addToBasket(int $id)
{
    $session_id = session_id(); // Записываем в переменную id сессии
    $sql = "INSERT INTO `basket` (`session_id`, `goods_id`) VALUES ('{$session_id}', '{$id}')"; // Записываем в
    // переменную SQL запрос на добавление товара в корзину для текущей сессии

    return executeQuery($sql); // Возвращаем результат выполнения функции
}

// Функция удаляет товар с id = $id из корзины для текущей сессии
function deleteFromBasket(int $id)
{
    $session_id = session_id(); // Записываем в переменную id сессии
    $sql = "DELETE FROM `basket` WHERE `basket`.`id` = {$id} AND `session_id`='$session_id'"; // Записываем в
    // переменную SQL запрос на удалени товара из корзины для текущей сессии

    return executeQuery($sql); // Возвращаем результат выполнения функции
}