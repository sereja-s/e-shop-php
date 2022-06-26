<?php

define('VG_ACCESS', true);  // Определение константы безопасности

// FRONT CONTROLLER

// Общие настройки
ini_set('display_errors', 1); // Отображение ошибок при разработке проекта (на готовом проекте отключается)
error_reporting(E_ALL);

// запускаем сессию
// (сессия- это механизм, который позволяет запоминать определённые данные о пользователе на сервере)
session_start();

// Подключение файлов системы
require_once 'components/functions.php'; //подключение отладочной функции
require_once 'config/config.php'; // Подключение констант конфигурации системы
require_once 'config/startup_settings.php'; // Подключение сервисных функций и настроек системы

use components\Router;
use exceptions\RouteException;

// для подключения файлов используем полный путь к файлам на диске
define('ROOT', dirname(__FILE__));

//var_dump(ROOT);

// Вызов Router 
// создадим экземпляр класса Router и запустим метод: run()
try {
	Router::getInstance()->run();
} catch (RouteException $e) {
	exit($e->getMessage());
}
