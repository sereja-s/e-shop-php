<?php

namespace models;

use components\Db;

/**
 * Класс Product - модель для работы с товарами
 */

class Product
{
	// Количество отображаемых товаров по умолчанию
	const SHOW_BY_DEFAULT = 6;

	/**
	 * Возвращает массив новых товаров (новинки)
	 * @param type $count [optional] <p>Количество</p>
	 * @param type $page [optional] <p>Номер текущей страницы</p>
	 * @return array <p>Массив с товарами</p>
	 */
	// на вход: количество товаров, которые хотим получить
	public static function getLatestProducts($count = self::SHOW_BY_DEFAULT)
	{
		// Соединение с БД
		$db = Db::getConnection();

		$productsList = [];

		// Текст запроса к БД
		$sql = 'SELECT id, name, price, is_new FROM product '
			. 'WHERE status = "1" AND is_new = "1" ORDER BY id DESC '
			. 'LIMIT :count';

		// Используется подготовленный запрос
		$result = $db->prepare($sql);
		$result->bindParam(':count', $count, \PDO::PARAM_INT);

		// Указываем, что хотим получить данные в виде массива
		// индексированного именами столбцов результирующего набора
		$result->setFetchMode(\PDO::FETCH_ASSOC);

		// Выполнение команды
		$result->execute();

		// Получение и возврат результатов
		$i = 0;

		// кладём данные в результирующий массив
		while ($row = $result->fetch()) {
			$productsList[$i]['id'] = $row['id'];
			$productsList[$i]['name'] = $row['name'];
			$productsList[$i]['price'] = $row['price'];
			$productsList[$i]['is_new'] = $row['is_new'];
			$i++;
		}

		return $productsList;
	}

	/**
	 * Возвращает список рекомендуемых товаров
	 * @return array <p>Массив с товарами</p>
	 */
	public static function getRecommendedProducts()
	{
		// Соединение с БД
		$db = Db::getConnection();

		$productsList = [];

		$sql = 'SELECT id, name, price, is_new FROM product '
			. 'WHERE status = "1" AND is_recommended = "1" '
			. 'ORDER BY id DESC';

		// Получение результатов запроса (из БД)
		$result = $db->query($sql);

		$i = 0;

		// выводим результаты в цикле и сохраняем в виде массива 
		while ($row = $result->fetch()) {
			$productsList[$i]['id'] = $row['id'];
			$productsList[$i]['name'] = $row['name'];
			$productsList[$i]['price'] = $row['price'];
			$productsList[$i]['is_new'] = $row['is_new'];
			$i++;
		}

		return $productsList;
	}


	/* public static function getRecommendedProducts($count = self::SHOW_BY_DEFAULT)
	{
		// Соединение с БД
		$db = Db::getConnection();

		$productsList = [];

		$sql = 'SELECT id, name, price, image, is_new FROM product '
			. 'WHERE status = "1" AND is_recommended = "1"'
			. 'ORDER BY id DESC '
			. 'LIMIT :count ';

		// Используется подготовленный запрос
		$result = $db->prepare($sql);
		$result->bindParam(':count', $count, \PDO::PARAM_INT);

		// Указываем, что хотим получить данные в виде массива
		// индексированного именами столбцов результирующего набора
		$result->setFetchMode(\PDO::FETCH_ASSOC);

		// Выполнение команды
		$result->execute();

		// Получение и возврат результатов

		$i = 0;

		// кладём данные в результирующий массив
		while ($row = $result->fetch()) {
			$productsList[$i]['id'] = $row['id'];
			$productsList[$i]['name'] = $row['name'];
			$productsList[$i]['price'] = $row['price'];
			$productsList[$i]['image'] = $row['image'];
			$productsList[$i]['is_new'] = $row['is_new'];
			$i++;
		}		

		return $productsList;
	} */

	/**
	 * Возвращает список товаров в указанной категории
	 * @param type $categoryId <p>id категории</p>
	 * @param type $page [optional] <p>Номер страницы</p>
	 * @return type <p>Массив с товарами</p>
	 */
	public static function getProductsListByCategory($categoryId, $page = 1)
	{
		$limit = Product::SHOW_BY_DEFAULT;
		// Смещение для запроса (при построении постраничной навигации)
		$offset = ($page - 1) * self::SHOW_BY_DEFAULT;

		// Соединение с БД
		$db = Db::getConnection();

		$products = [];

		// Текст запроса к БД
		$sql = 'SELECT id, name, price, image, is_new FROM product '
			. 'WHERE status = 1 AND category_id = :category_id '
			. 'ORDER BY id ASC LIMIT :limit OFFSET :offset';

		// Используется подготовленный запрос
		$result = $db->prepare($sql);
		$result->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
		$result->bindParam(':limit', $limit, \PDO::PARAM_INT);
		$result->bindParam(':offset', $offset, \PDO::PARAM_INT);

		// Выполнение команды
		$result->execute();

		$i = 0;

		while ($row = $result->fetch()) {
			$products[$i]['id'] = $row['id'];
			$products[$i]['name'] = $row['name'];
			$products[$i]['price'] = $row['price'];
			$products[$i]['image'] = $row['image'];
			$products[$i]['is_new'] = $row['is_new'];
			$i++;
		}

		return $products;
	}


	/**
	 * Возвращает продукт с указанным id
	 * @param integer $id <p>id товара</p>
	 * @return array <p>Массив с информацией о товаре</p>
	 */
	public static function getProductById($id)
	{
		// Соединение с БД
		$db = Db::getConnection();

		// Текст запроса к БД
		$sql = 'SELECT * FROM product WHERE id = :id';

		// Используется подготовленный запрос
		$result = $db->prepare($sql);
		$result->bindParam(':id', $id, \PDO::PARAM_INT);

		// Указываем, что хотим получить данные в виде массива
		// индексированного именами столбцов результирующего набора
		$result->setFetchMode(\PDO::FETCH_ASSOC);

		// Выполнение команды
		$result->execute();

		// Получение и возврат результатов
		return $result->fetch();
	}

	/**
	 * Возвращаем количество товаров в указанной категории
	 * @param integer $categoryId <p>id категории</p>
	 * @return integer
	 */
	public static function getTotalProductsInCategory($categoryId)
	{
		// Соединение с БД
		$db = Db::getConnection();

		// Текст запроса к БД
		$sql = 'SELECT count(id) AS count FROM product WHERE status="1" AND category_id = :category_id';

		// Используется подготовленный запрос
		$result = $db->prepare($sql);
		$result->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);

		// Выполнение команды
		$result->execute();

		// Возвращаем значение count - количество
		$row = $result->fetch();

		if ($row != false) {
			return $row['count'];
		}
	}

	/**
	 * Возвращает из БД информацию о товарах по указанным индентификторами
	 * @param array $idsArray <p>Массив с идентификаторами</p>
	 * @return array <p>Массив со списком товаров</p>
	 */
	public static function getProductsByIds($idsArray)
	{
		// Соединение с БД
		$db = Db::getConnection();

		// Превращаем массив в строку для формирования условия в запросе
		$idsString = implode(',', $idsArray);

		// Текст запроса к БД
		$sql = "SELECT * FROM product WHERE status='1' AND id IN ($idsString)";

		$result = $db->query($sql);

		// Указываем, что хотим получить данные в виде массива
		// индексированного именами столбцов результирующего набора
		$result->setFetchMode(\PDO::FETCH_ASSOC);

		// Получение и возврат результатов
		$i = 0;
		$products = [];
		while ($row = $result->fetch()) {
			$products[$i]['id'] = $row['id'];
			$products[$i]['code'] = $row['code'];
			$products[$i]['name'] = $row['name'];
			$products[$i]['price'] = $row['price'];
			$i++;
		}

		return $products;
	}



	/**
	 * Возвращает список товаров
	 * @return array <p>Массив с товарами</p>
	 */
	public static function getProductsList()
	{
		// Соединение с БД
		$db = Db::getConnection();

		// Получение и возврат результатов
		$result = $db->query('SELECT id, name, price, code FROM product ORDER BY id ASC');
		$productsList = [];
		$i = 0;
		while ($row = $result->fetch()) {
			$productsList[$i]['id'] = $row['id'];
			$productsList[$i]['name'] = $row['name'];
			$productsList[$i]['code'] = $row['code'];
			$productsList[$i]['price'] = $row['price'];
			$i++;
		}
		return $productsList;
	}

	/**
	 * Удаляет товар с указанным id
	 * @param integer $id <p>id товара</p>
	 * @return boolean <p>Результат выполнения метода</p>
	 */
	public static function deleteProductById($id)
	{
		// Соединение с БД
		$db = Db::getConnection();

		// Текст запроса к БД (используем подготовленный запрос)
		$sql = 'DELETE FROM product WHERE id = :id';

		// Получение и возврат результатов. Используется подготовленный запрос
		$result = $db->prepare($sql);
		$result->bindParam(':id', $id, \PDO::PARAM_INT);
		return $result->execute();
	}

	/**
	 * Редактирует товар с заданным id
	 * @param integer $id <p>id товара</p>
	 * @param array $options <p>Массив с информацей о товаре</p>
	 * @return boolean <p>Результат выполнения метода</p>
	 */
	public static function updateProductById($id, $options)
	{
		// Соединение с БД
		$db = Db::getConnection();

		// Текст запроса к БД
		$sql = "UPDATE product
            SET 
                name = :name, 
                code = :code, 
                price = :price, 
                category_id = :category_id, 
                brand = :brand, 
                availability = :availability, 
                description = :description, 
                is_new = :is_new, 
                is_recommended = :is_recommended, 
                status = :status
            WHERE id = :id";

		// Получение и возврат результатов. Используется подготовленный запрос
		$result = $db->prepare($sql);
		$result->bindParam(':id', $id, \PDO::PARAM_INT);
		$result->bindParam(':name', $options['name'], \PDO::PARAM_STR);
		$result->bindParam(':code', $options['code'], \PDO::PARAM_STR);
		$result->bindParam(':price', $options['price'], \PDO::PARAM_STR);
		$result->bindParam(':category_id', $options['category_id'], \PDO::PARAM_INT);
		$result->bindParam(':brand', $options['brand'], \PDO::PARAM_STR);
		$result->bindParam(':availability', $options['availability'], \PDO::PARAM_INT);
		$result->bindParam(':description', $options['description'], \PDO::PARAM_STR);
		$result->bindParam(':is_new', $options['is_new'], \PDO::PARAM_INT);
		$result->bindParam(':is_recommended', $options['is_recommended'], \PDO::PARAM_INT);
		$result->bindParam(':status', $options['status'], \PDO::PARAM_INT);
		return $result->execute();
	}

	/**
	 * Добавляет новый товар
	 * @param array $options <p>Массив с информацией о товаре</p>
	 * @return integer <p>id добавленной в таблицу записи</p>
	 */
	public static function createProduct($options)
	{
		// Соединение с БД
		$db = Db::getConnection();

		// Текст запроса к БД
		$sql = 'INSERT INTO product '
			. '(name, code, price, category_id, brand, availability,'
			. 'description, is_new, is_recommended, status)'
			. 'VALUES '
			. '(:name, :code, :price, :category_id, :brand, :availability,'
			. ':description, :is_new, :is_recommended, :status)';

		// Получение и возврат результатов. Используется подготовленный запрос
		$result = $db->prepare($sql);
		$result->bindParam(':name', $options['name'], \PDO::PARAM_STR);
		$result->bindParam(':code', $options['code'], \PDO::PARAM_STR);
		$result->bindParam(':price', $options['price'], \PDO::PARAM_STR);
		$result->bindParam(':category_id', $options['category_id'], \PDO::PARAM_INT);
		$result->bindParam(':brand', $options['brand'], \PDO::PARAM_STR);
		$result->bindParam(':availability', $options['availability'], \PDO::PARAM_INT);
		$result->bindParam(':description', $options['description'], \PDO::PARAM_STR);
		$result->bindParam(':is_new', $options['is_new'], \PDO::PARAM_INT);
		$result->bindParam(':is_recommended', $options['is_recommended'], \PDO::PARAM_INT);
		$result->bindParam(':status', $options['status'], \PDO::PARAM_INT);
		if ($result->execute()) {
			// Если запрос выполенен успешно, возвращаем id добавленной записи
			return $db->lastInsertId();
		}
		// Иначе возвращаем 0
		return 0;
	}

	/**
	 * Возвращает текстое пояснение наличия товара:<br/>
	 * <i>0 - Под заказ, 1 - В наличии</i>
	 * @param integer $availability <p>Статус</p>
	 * @return string <p>Текстовое пояснение</p>
	 */
	public static function getAvailabilityText($availability)
	{
		switch ($availability) {
			case '1':
				return 'В наличии';
				break;
			case '0':
				return 'Под заказ';
				break;
		}
	}


	/**
	 * Возвращает путь к изображению
	 * @param integer $id
	 * @return string <p>Путь к изображению</p>
	 */
	public static function getImage($id)
	{
		// Название изображения-пустышки
		$noImage = 'no-image.jpg';

		// Путь к папке с товарами
		$path = '/upload/images/products/';

		// Путь к изображению товара
		$pathToProductImage = $path . $id . '.jpg';

		if (file_exists($_SERVER['DOCUMENT_ROOT'] . $pathToProductImage)) {
			// Если изображение для товара существует
			// Возвращаем путь изображения товара
			return $pathToProductImage;
		}

		// Возвращаем путь изображения-пустышки
		return $path . $noImage;
	}
}
