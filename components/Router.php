<?php

namespace components;

use exceptions\RouteException;

/**
 * Класс Router
 * Компонент для работы с маршрутами
 */
class Router
{
	static private $_instace;

	/**
	 * Свойство для хранения массива роутов (маршрутов)
	 * @var array 
	 */
	private $routes;
	/**
	 * Имя контроллера
	 * @var type string
	 */
	private $controller = 'index';
	/**
	 * Имя метода
	 * @var type 
	 */
	private $action = 'index';
	/**
	 * Передаваемые данные
	 * @var type 
	 */

	private $parameters;

	private function __clone()
	{
	}

	static public function getInstance()
	{

		if (self::$_instace instanceof self) {
			return self::$_instace;
		}

		return self::$_instace = new self;
	}

	/**
	 * Конструктор в котором прочитаем и запомним роуты (маршруты) на время выполнения кода
	 */
	private function __construct()
	{
		// получаем массив правил получения внутренних путей из внешних (массив маршрутов)
		$this->routes = routes();

		// получение содержимого адресной сторки (запрос пользователя)
		$uri = self::getURI();

		// Проверяем наличие такого запроса в массиве маршрутов
		foreach ($this->routes as $uriPattern => $path) {

			// Сравниваем $uriPattern и $uri (preg_match - проверка на соответсвие регулярному выражению)
			// (ищем совпадение того что содержится в маршрутах (роутах) с тем что попало в строку запроса пользоаателя)
			if (preg_match("~$uriPattern~", $uri)) {

				// Получаем внутренний путь из внешнего согласно правилу (preg_replace - поиск и замена по регулярному выражению)
				// В качестве разделителей в регулярном выражении используется знак ~ (тильда) вместо / (слеш) т.к. они могут содержаться в шаблоне
				$internalRoute = preg_replace("~$uriPattern~", $path, $uri);

				//echo $path;

				//echo '<br>Где ищем (запрос, который набрал пользователь): ' . $uri;
				//echo '<br>Что ищем (параметры (их совпадение шаблону)): ' . $uriPattern;
				//echo '<br>Что обрабатывает (запрос, который набрал пользователь): ' . $uriPattern;

				// Определить какой контроллер и action обрабатывают запрос, параметры

				// explode()- Разделяет строку по разделителю (здесь- / )
				// Возвращает массив строк полученных при разделении (получим 2-а элемента)
				$segments = explode('/', $internalRoute);

				/* echo '<pre>';
				var_dump($segments);
				echo '</pre>'; */

				// получим имя контроллера
				// array_shift()- получает значение первого элемента в массиве и удаляет его из массива
				// далее конкатенируем строку: Controller
				$controllerName = array_shift($segments) . 'Controller';

				//echo $controllerName;

				// Преобразует первый символ строки в верхний регистр (приведём имя контроллера к принятому нами именованию)
				$this->controller = ucfirst($controllerName);

				//echo $this->controller;

				// определение метода (экшена)
				$this->action = 'action' . ucfirst(array_shift($segments));

				//echo $this->action;

				//echo '<br>Имя контроллера: ' . $this->controller;
				//echo '<br>Метод (экшен): ' . $this->action;

				// сохраняем массива параметров (если они есть, то только они останутся в массиве)
				$this->parameters = $segments;

				//echo '<pre>';
				//print_r($this->parameters);
				//die;

				return;
			}
		}
	}

	/**
	 * Возвращает строку запроса
	 */
	private function getURI()
	{
		// получение адресной сторки
		$uri = $_SERVER['REQUEST_URI'];
		// если адресная строка заканчивается слешем и не является корнем сайта
		if (strrpos($uri, '/') === strlen($uri) - 1 && strrpos($uri, '/') !== 0) {
			// strrpos - позиция последнего вхождения отсчитывается от 0
			// strlen - длина строки считается с 1 позиции
			// удаляем слэш из конца строки
			//return rtrim($uri, '/')
			// то перенаправляем на адрес без слеша
			$uri = rtrim($uri, '/');
			header("Location: {$uri}");
		}
		return trim($uri, '/');

		//echo $uri;
	}

	/**
	 * Метод для вызова методов маршрутов (анализ запроса и передача управления)
	 */
	public function run()
	{

		/* echo '<pre>';
		print_r($this->routes);
		echo '</pre>'; */

		//echo 'Class Router, method run';

		// Подключить файл класса-контроллера
		// (определяем файл, который нужно подключить)

		// (здесь- PATH_PREFIX = controllers/)
		$controller = str_replace('/', '\\', PATH_PREFIX . $this->controller);

		//echo PATH_PREFIX;

		try {

			// создаём объект класса и для него вызываем метод (экшен) и результат сохраняем в переменной: $page
			$page = new \ReflectionMethod($controller, "$this->action"); // первый параметр- имя класса, второй- имя метода

			// для объекта: new $controller, вызовается метод (здесь- $this->action) с параметрами: $this->parameters
			$page->invoke(new $controller, $this->parameters);
		} catch (\ReflectionException $e) {
			throw new RouteException('Страница не существует');
			// throw new RouteException($e);
		}
	}
}
