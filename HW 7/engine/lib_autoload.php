<?php

/* Файл для автоматической загрузки модулей */

$lib_file = array_splice(scandir(MODULES_DIR), 2); // Сканируем директорию где хранятся модули

foreach ($lib_file as $file) {
    require_once MODULES_DIR . $file;
}