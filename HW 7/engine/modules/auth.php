<?php

/* Файл с функциями аутентификации */

function makeHashAuth()
{
    $hash = uniqid(rand(), true); // Записываем в переменную случайное значение hash
    $db = getDb(); // Записываем в переменную БД запрашивая ее из функции
    $id = mysqli_real_escape_string($db, strip_tags(stripslashes($_SESSION['id']))); // Записываем
    // в перменную безопасное значение id из массива $_SESSION
    $sql = "UPDATE `users` SET `hash` = '{$hash}' WHERE `users` . `id` = '{$id}'"; // Записываем в
    // переменную SQL запрос на обновление значения поля hash у пользователя
    $result = executeQuery($sql); // Записываем в переменную результат выполнения запроса
    setcookie("hash", $hash, time() + 3600, "/"); // Создаем cookie со значением переменной hash
}

// Функция осуществляет запрос на получение пользователя из БД по полю login и сравнивает введенное пользователем
// значение пароля с хранимым значением на сервере
function auth(string $login, string $password)
{
    $db = getDb(); // Записываем в переменную БД запрашивая ее из функции
    $login = mysqli_real_escape_string($db, strip_tags(stripslashes($login))); // Записываем
    // в перменную безопасное значение login из параметра $login
    $sql = "SELECT * FROM `users` WHERE `login` = '{$login}'"; // Записываем в переменную SQL запрос на поиск
    // пользователя с именем $login
    $result = getAssocResult($sql); // Записываем в переменную сформированный ассоциативный массив

    if (password_verify($password, $result[0]['password'])) { // Если введенный пользователем пароль совпадает с
        //паролем на на сервере
        $_SESSION['login'] = $login; // Записываем в сессию login
        $_SESSION['id'] = $result['id']; // Записываем в сессию id

        return true;
    }

    return false;
}

// Функция выполняется на каждой страние и проверяет авторизован ли кто либо
function is_auth()
{
    if (isset($_COOKIE["hash"])) { // Если пользователь установил флаг в поле "Запомнить" проверяем по cookie
        $hash = $_COOKIE["hash"]; // Записываем в переменную cookie с именем hash
        $db = getDb(); // Записываем в переменную БД запрашивая ее из функции
        $sql = "SELECT * FROM `users` WHERE `hash` = '{$hash}'"; // Записываем в переменную SQL запрос на поиск
        // пользователя по значению $hash
        $result = mysqli_query($db, $sql); // Записываем в переменную результат выполнения запроса
        $row = mysqli_fetch_assoc($result); // Записываем в переменную сформированный в результате выполнения функции
        // ассоциативный массив
        $user = $row['login']; // Записываем в переменную значение свойства login из ассоциативного массива
        if (!empty($user)) { // Если пользователь со значением $hash есть в БД
            $_SESSION['login'] = $user; // Устанавливаем сессию
        }
    }

    // Далее переходим к return и сразу проверяем существует ли сессия с установленным значением login
    return isset($_SESSION['login']) ? true : false; //
}

// Функция возвращает login пользователя из массива $_SESSION
function get_user()
{
    return isset($_SESSION['login']) ? $_SESSION['login'] : "Guest";
}

// Функция возвращает статусные сообщения
function getStatusRegistration($login, $password)
{
    if (empty($login) || empty($password)) {
        return "!!! Проверьте введен ли логин и пароль";
    } else {
        return userRegistration($login, $password);
    }
}

// Функция добавляет пользователя в БД и возвращает сообщение о статусе выполнения
function userRegistration($login, $password)
{
    $login = strip_tags(stripcslashes(htmlspecialchars($login)));
    $check_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO `users` (`login`, `password`, `hash`) values ('{$login}', '{$check_password}', '')";

    if (executeQuery($sql)) {
        return "Благодарим за регистрацию в нашем интернет-магазине $login.<br>
                Теперь вы можете выполнить вход, используя введенные вами данные.";
    } else {
        return "Что-то пошло не так, пожалуйста повторите регистрацию";
    }
}