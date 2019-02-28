<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Модель для работы с постами
 */
class Posts_model extends CI_Model
{
	/**
	 * ID пользователя, оставившего пост
	 * @var int
	 */
	public $user_id;
	/**
	 * Текст поста
	 * @var string
	 */
	public $text;
	/**
	 * Размер постранички
	 */
	const PAGE_SIZE = 3;
	/**
	 * Максимальная длина поста
	 */
	const MAX_POST_LENGTH = 300;

	/**
	 * Возвращает число страниц
	 */
	public function getPagesCount()
	{
		$cursor = $this->db->query("SELECT COUNT(`id`) AS `count` FROM `posts`")->result();
		$count = intval($cursor[0]->count);
		if ($count === 0) {
			return 1;
		}
		if (($count % self::PAGE_SIZE) == 0) {
			return $count / self::PAGE_SIZE;
		}
		return floor($count / self::PAGE_SIZE) + 1;
	}

	/**
	 * Возвращает страницу с постарами
	 * @param int $pageIndex индекс страницы
	 * @return array
	 */
	public function getPage($pageIndex)
	{
		if (!is_string($pageIndex) && !is_numeric($pageIndex)) {
			return array();
		}
		$pageIndex = intval($pageIndex);
		if ($pageIndex <= 0) {
			return array();
		}
		$offset = ($pageIndex - 1) * self::PAGE_SIZE;
		$sql = 'SELECT `posts`.`id`, `posts`.`user_id`, `users`.`email` AS `user`, `posts`.`text`, DATE_FORMAT(`posts`.`date`, \'%d.%m.%Y\') AS `date` FROM `posts` LEFT JOIN `users` ON `users`.`id` = `posts`.`user_id` ORDER BY `id` ASC LIMIT ?, ?';
		$binds = array($offset, self::PAGE_SIZE);
		$result = $this->db->query($sql, $binds)->result();
		if (count($result)) {
			foreach($result as $item) {
				$item->text = htmlspecialchars($item->text);
			}
		}
		return $result;
	}

	/**
	 * Создаёт новый пост
	 * @param int $userId ID пользователя
	 * @param string $text текст текст пользователя
	 * @return int $id ID текста
	 * @throws \Exception исключение, если текст неверен или пользователь некорректный
	 */
	public function insert($userId, $text)
	{
		$this->throwExceptionIfUserIsInvalid($userId, "Авторизуйтесь, чтобы добавить новый пост");

		$textIsInvalid = true;
		if (is_string($text)) {
			$text = trim($text);
			if (mb_strlen($text) != 0) {
				$textIsInvalid = false;
			}
		}
		if ($textIsInvalid) {
			throw new \Exception("В посте должен быть заполнен  текст");
		}
		$maxLength = self::MAX_POST_LENGTH;
		if (mb_strlen($text) > $maxLength) {
			throw new \Exception("Текст не должен превышать по длине $maxLength символов");
		}

		$data = array(
			'user_id'  => $userId,
			'text'    => $text,
			'date' => date("Y-m-d")
		);
		$this->db->insert('posts', $data);
		$id = $this->db->insert_id();
		return $id;
	}


	/**
	 * Удаляет пост по ID и ID пользователя
	 * @param int $id ID поста
	 * @param int $userId ID пользователя
	 * @throws Exception исключение, если ID поста некорректный
	 */
	public function delete($id, $userId)
	{
		$this->throwExceptionIfUserIsInvalid($userId, "Авторизуйтесь, чтобы удалить свои посты");

		$idIsInvalid = true;
		if (is_string($id) || is_numeric($id)) {
			$id = intval($id);
			if ($id > 0) {
				$idIsInvalid = false;
			}
		}
		if ($idIsInvalid) {
			throw  new \Exception("Некорректный ID поста");
		}

		$sql = 'SELECT COUNT(`id`) AS `count` FROM `posts` WHERE `id` = ? AND `user_id` = ? LIMIT 1' ;
		$binds = array($id, $userId);
		$cursor =  $this->db->query($sql, $binds)->result();
		$count = intval($cursor[0]->count);
		if ($count <= 0) {
			throw new \Exception("Пост не найден");
		}

		$sql = 'DELETE FROM `posts` WHERE `id` = ? AND `user_id` = ?' ;
		$binds = array($id, $userId);
		$this->db->query($sql, $binds);
	}

	/**
	 * Бросает исключение, если ID пользователя неверен
	 * @param int $userId ID пользователя
	 * @param string сообщение в исключении
	 * @return bool результат
	 * @throws Exception
	 */
	private function throwExceptionIfUserIsInvalid($userId, $message)
	{
		$userIdIsInvalid = true;
		if (is_string($userId) || is_numeric($userId)) {
			$userId = intval($userId);
			if ($userId > 0) {
				$sql = 'SELECT COUNT(`id`) AS `count` FROM `users` WHERE `id` = ? LIMIT 1' ;
				$binds = array($userId);
				$cursor =  $this->db->query($sql, $binds)->result();
				$count = intval($cursor[0]->count);
				if ($count > 0) {
					$userIdIsInvalid = false;
				}
			}
		}

		if ($userIdIsInvalid) {
			throw new \Exception($message);
		}
		return true;
	}
}