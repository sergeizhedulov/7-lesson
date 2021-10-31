<?php

/* Файл с функциями рендера */

// Функция возвращает текст шаблона $page с подстановкой переменных
// из массива $params, содержимое шабона $page подставляется в
// переменную $content главного шаблона layout.php для всех страниц
function render($page, $params = [])
{
    return renderTemplate(LAYOUTS_DIR . 'layout', [
            'content' => renderTemplate($page, $params),
            'menu' => renderTemplate('menu', $params)
        ]
    );
}

// Функция возвращает текст шаблона $page с подставленными переменными из
// массива $params
function renderTemplate($page, $params = [])
{
    ob_start();

    if (!is_null($params))
        extract($params);

    $fileName = TEMPLATES_DIR . $page . ".php";

    if (file_exists($fileName)) {
        include $fileName;
    } else {
        Die("Страницы не существует. 404");
    }

    return ob_get_clean();
}