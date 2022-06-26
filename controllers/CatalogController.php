<?php

namespace controllers;

use models\Category;
use models\Product;

use components\Pagination;

/**
 * Контроллер CatalogController
 * Каталог товаров
 */
class CatalogController
{

	/**
	 * Action для страницы "Каталог товаров"
	 */
	public function actionIndex()
	{
		// Список категорий для левого меню
		$categories = Category::getCategoriesList();

		// Список новинок
		$latestProducts = Product::getLatestProducts(9);
		// Подключаем вид
		require_once('views/catalog/index.php');

		return true;
	}

	/**
	 * Action для страницы "Категория товаров"
	 * 
	 * @param type $getId <p>id отображаемой категории</p>
	 */
	public function actionCategory($getParam)
	{
		// Инициализация категории и страницы
		$categoryId = (is_array($getParam)) ? array_shift($getParam) : $getParam;
		$page = array_shift($getParam);
		$page = $page ?? 1;

		// Список категорий для левого меню
		$categories = Category::getCategoriesList();

		// Список товаров в категории
		$categoryProducts = Product::getProductsListByCategory($categoryId, $page);

		// Общее количетсво товаров (необходимо для постраничной навигации)
		$total = Product::getTotalProductsInCategory($categoryId);

		// Создаем объект класса Pagination- постраничная навигация На вход (в конструктор) передаём параметры:
		// 1- общее кол-во товаров конкретной категории, 2- номер страницы (пришёл в экшен как параметр), 3- константа класса
		// Product (кол-во товаров на страницу по умолчанию) 4- ключ (из URL) определили в маршрутах
		$pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

		// Подключаем вид
		require_once('views/catalog/category.php');

		return true;
	}
}
