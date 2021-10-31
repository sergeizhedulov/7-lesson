<?php

/* Файл контроллера */

// Функция подготовки переменных для передачи их в шаблон
function prepareVariables(string $page, string $action, string $id)
{
    // Переменные для ВСЕХ страниц.
    $params = ['count' => getBasketCount()];
    $params['allow'] = false;

    if (is_auth()) { // Если есть совпадение по cookie или сессии
        $params['allow'] = true; // Разрешаем вход
        $params['user'] = get_user(); // Приветствуем пользователя
    }

// Для каждой страницы подготавливаем массив со своим набором переменных для подстановки их в соотвествующий шаблон
    switch ($page) { // Ориентируемся на значение переменной $page

        case 'auth': // http://shop/auth/
            if ($action == "login") { // Если выполнен запрос на аутентификацию пользователя

                if (isset($_POST['send'])) { // Если поступил запрос на отправку формы
                    $login = $_POST['login']; // Записываем в переменную значение поля login
                    $password = $_POST['password']; // Записываем в переменную значение поля password

                    if (!auth($login, $password)) { // Если пользователя с таким логином и паролем нет в БД сообщаем
                        // пользователю об ошибке
                        Die('Не верный логин или пароль');
                    } else { // Если пользователь с таким логином и паролем есть в БД

                        if (isset($_POST['save'])) { // Если пользователь установил флаг в поле "Запомнить"
                            makeHashAuth();
                            header("Location: {$_SERVER['HTTP_REFERER']}"); // Перезагружаемся на предыдущую страницу
                        }

                        header("Location: {$_SERVER['HTTP_REFERER']}"); // Перезагружаемся на предыдущую страницу
                    }
                }
            }

            if ($action == "logout") { // Если выполнен запрос на выход пользователя
                session_unset(); // Удаляем все переменные сессии
                session_destroy(); //Уничтожаем все данные сессии
                setcookie("hash", "", time() - 3600, "/"); // Удаляем cookie со значением переменной hash
                header("Location: {$_SERVER['HTTP_REFERER']}"); // Перезагружаемся на предыдущую страницу
            }

            break;

        case 'registration': // http://shop/registration/

            if (isset($_POST['registration'])) {
                $login = $_POST['login'];
                $password = $_POST['password'];
                $params['statusRegistration'] = getStatusRegistration($login, $password);
            }
            break;

        case 'api': // Пример URL: /api/buy/5

            if ($action == "buy") {
                addToBasket($id);

                echo json_encode([
                    "result" => 1,
                    "count" => getBasketCount()
                ]);
                exit;
            }

            if ($action == "delete") {
                deleteFromBasket($id);

                echo json_encode([
                    "result" => 1,
                    "count" => getBasketCount(),
                    "totalCost" => totalCost()
                ]);
                exit;
            }
            break;

        case 'index': // http://shop/
            $params['name'] = 'Power To Play';
            break;

        case 'goods': // http://shop/goods/
            $params['goods'] = getGoods();
            break;

        case 'goods_item': // http://shop/goods_item/
            $params['goods_item'] = getGoodsItem($id);
            break;

        case 'gallery': // http://shop/gallery/
            $params['images'] = getImages();
            break;

        case 'gallery_item': // http://shop/gallery_item/
            addLike($id);
            $params['images_item'] = getFullImage($id);
            break;

        case 'news': // http://shop/news/
            $params['news'] = getNews();
            break;

        case 'newspage': // http://shop/newspage/
            $content = getNewsContent($id);
            $params['prevew'] = $content['prevew'];
            $params['full'] = $content['full'];
            break;

        case 'feedback': // http://shop/feedback/
            doFeedbackAction($params, $action, $id);
            $params['feedback'] = getAllFeedback();
            break;

        case 'basket': // http://shop/basket/
            $params['basket'] = getBasket();
            $params['totalCost'] = totalCost();
            break;

    }

    return $params;
}