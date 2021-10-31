<?php

/* Файл с функциями отзывов */

function doFeedbackAction(&$params, $action, $id)
{
    /* 2-й вариант подключения массива ошибок для страницы "Отзывы" */
//    $errorsArr = include "../config/errors.php";
//    $params['error'] = $errorsArr[$_GET['status']];

    // Подключение массива ошибок для страницы "Отзывы"
    $params['error'] = ERR_CODE[$_GET['status']];

    // Значения по умолчанию.
    $params['action'] = "add";
    $params['buttonText'] = "Добавить";
    $params['name'] = "";
    $params['feedtext'] = "";

    // Добавить
    if ($action == "add") {
        if (addFeedBack()) {
            header("Location: /feedback/?status=OK");
        } else {
            header("Location: /feedback/?status=ERROR_ADD");
        }
    }

    // Удалить
    if ($action == "delete") {
        $error = deleteFeedBack($id);
        if ($error) {
            header("Location: /feedback/?status=DELETED");
        } else {
            header("Location: /feedback/?status=ERROR_DEL");
        }
    }

    // Сохранить
    if ($action == "save") {
        $error = updateFeedBack($id);
        if ($error) {
            header("Location: /feedback/?status=UPDATED");
        } else {
            header("Location: /feedback/?status=ERROR_UPDATED");
        }
    }

    // Изменить
    if ($action == "edit") {
        $feedback = getFeedback($id);
        $params['action'] = "save/{$id}";
        $params['buttonText'] = "Сохранить";
        $params['name'] = $feedback['name'];
        $params['feedtext'] = $feedback['feedback'];
    }
}

// Функция возвращает массив всех отзывов
function getAllFeedback()
{
    $sql = "SELECT * FROM `feedback`";

    return getAssocResult($sql);
}

// Функция возвращает результат выполнения sql запроса на добавление отзыва к общему массиву
function addFeedBack()
{
    $db = getDb();
    $name = mysqli_real_escape_string($db, strip_tags(htmlspecialchars($_POST['name'])));
    $message = mysqli_real_escape_string($db, strip_tags(htmlspecialchars($_POST['message'])));
    $sql = "INSERT INTO `feedback` (`name`, `feedback`) VALUES ('{$name}', '{$message}')";
    $result = executeQuery($sql);

    if (mysqli_affected_rows(getDb()) != 1) {
        return false;
    }

    return $result;
}

// Функция возвращает результат выполнения sql запроса на удаление отзыва из общего массива
function deleteFeedback($id)
{
    $id = (int)$id;
    $sql = "DELETE FROM `feedback` WHERE `id` = {$id}";
    $result = executeQuery($sql);

    if (mysqli_affected_rows(getDb()) != 1) {
        return false;
    }

    return $result;
}

// Функция возвращает результат выполнения sql запроса на редактировани отзыва
function updateFeedback($id)
{
    $db = getDb();
    $name = mysqli_real_escape_string($db, strip_tags(htmlspecialchars($_POST['name'])));
    $message = mysqli_real_escape_string($db, strip_tags(htmlspecialchars($_POST['message'])));
    $sql = "UPDATE `feedback` SET `name` = '{$name}', `feedback` = '{$message}' WHERE `id` = {$id}";
    $result = executeQuery($sql);

    if (mysqli_affected_rows(getDb()) != 1) {
        return false;
    }

    return $result;
}

// Функция возвращает выбранный для редактирования отзыв
function getFeedback($id)
{
    $id = (int)$id;
    $sql = "SELECT * FROM `feedback` WHERE `id` = {$id}";
    $result = getAssocResult($sql)[0];

    if (mysqli_affected_rows(getDb()) != 1) {
        return false;
    }

    return $result;
}