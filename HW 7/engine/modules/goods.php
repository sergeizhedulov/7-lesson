<?php

/* Файл с функциями товаров каталога */

// Функция возвращает массив всех товаров каталога
function getGoods()
{
    $sql = "SELECT * FROM `goods`";
    $goods = getAssocResult($sql);

    return $goods;
}

// Функция возвращает запрашиваемый товар каталога для подробного просмотра
function getGoodsItem($id)
{
    $id = (int)$id;
    $sql = "SELECT * FROM `goods` WHERE `id` = {$id}";
    $goods_item = getAssocResult($sql);

    // В случае если товара нет, вернем пустое значение
    $result = [];
    if (isset($goods_item[0]))
        $result = $goods_item[0];

    return $result;
}