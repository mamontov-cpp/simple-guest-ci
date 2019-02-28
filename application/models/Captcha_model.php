<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Вспомогательная модель для работы с капчей
 */
class Captcha_model extends CI_Model
{
	/**
	 * Время на истечение капчи
	 */
	const EXPIRATION_TIME = 7200;
	/**
	 * Загрузка хелпера для капчи
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('captcha');
	}

	/**
	 * Удаляет старые записи в таблице. Не идеально, по идее будет лучше утащить на CRON.
	 */
	public function deleteOldEntries()
	{
		$expiration = time() - self::EXPIRATION_TIME; // Two hour limit
		$this->db->where('captcha_time < ', $expiration)->delete('captcha');
	}

	/**
	 * Создание капчи для задачи
	 * @return array массив с id - данные о капче, и  результатом @see create_captcha
	 */
	public function makeCAPTCHAEntry()
	{
		$vals = array(
			'img_path'      => $_SERVER['DOCUMENT_ROOT'] . '/captcha/',
			'img_url'       => '/captcha/',
			'img_width'     => '150',
			'img_height'    => 30,
			'expiration'    => self::EXPIRATION_TIME,
			'word_length'   => 8,
			'font_size'     => 16,
			'img_id'        => 'Imageid',
			'pool'          => '12345789ABCDEFHIJKLMNPRSTUVWXYZ',

			// Белый фон, черный текст, сеточка
			'colors'        => array(
				'background' => array(255, 255, 255),
				'border' => array(255, 255, 255),
				'text' => array(0, 0, 0),
				'grid' => array(255, 40, 40)
			)
		);

		$cap = create_captcha($vals);
		$data = array(
			'captcha_time'  => $cap['time'],
			'ip_address'    => $this->input->ip_address(),
			'word'          => $cap['word']
		);
		$this->db->insert('captcha', $data);
		$id = $this->db->insert_id();
		$cap['id'] = $id;
		/** @noinspection PhpIncompatibleReturnTypeInspection */
		return $cap;
	}

	/**
	 * Возвращает результат проверки КАПЧИ
	 * @param int $captchaId ID капчт
	 * @param string $captchaWord слова капчи
	 * @return stdClass с двумя полями bool ok, если результат успешен,  string message - сообщение
	 */
	public function checkCAPTCHA($captchaId, $captchaWord)
	{
		$ok = false;
		$errorResult = "Пожалуйста, введите символы на картинке";
		if ($captchaId !== null && $captchaWord !== null) {
			if ((is_string($captchaId) || is_int($captchaId)) && is_string($captchaWord)) {
				$captchaId = intval($captchaId);
				if ($captchaId > 0) {
					$sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND captcha_id = ? AND captcha_time > ?';
					$binds = array($captchaWord, $captchaId, self::EXPIRATION_TIME);
					$query = $this->db->query($sql, $binds);
					$row = $query->row();

					if ($row->count != 0)
					{
						$this->db->where('captcha_id = ', $captchaId)->delete('captcha');
						$ok = true;
						$errorResult = "";
					}
				}
			}
		}
		$result = new stdClass();
		$result->ok = $ok;
		$result->message = $errorResult;
		return $result;
	}


}