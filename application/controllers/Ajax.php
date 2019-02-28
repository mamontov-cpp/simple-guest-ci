<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Контроллер Ajax-страниц
 * @class Ajax
 */
class Ajax extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('string');
		$this->load->helper('url');
		$this->load->helper('security');

		$this->load->database();
	}
	/**
	 * Логика для работы системы с комментариями
	 */
	public function posts($type)
	{
		if ($type == "add") {
			$this->_add();
		} else {
			show_404();
		}
	}

	/**
	 * Добавление поста
	 */
	private function _add()
	{
		$this->load->model("captcha_model");

		$this->load->model("posts_model");
		// Т.к. страница добавления, то достаточно лишь вывести данные JSON, в MVC смысла особого нет
		$result = array();
		if ($this->input->method(true) == "POST") {
			if ($this->ion_auth->logged_in()) {
				$this->captcha_model->deleteOldEntries();
				$captchaValidationResult = $this->captcha_model->checkCAPTCHA($this->input->post("captcha_id"), $this->input->post("captcha_word"));
				if ($captchaValidationResult->ok || true ) {
					$userId = $this->ion_auth->get_user_id();
					$text = $this->input->post("text");
					try {
						$this->posts_model->insert($userId, $text);
						$result["ok"] = true;
						$result["pages"] = $this->posts_model->getPagesCount();
						$result["current_page"] = $result["pages"];
						$result["page"] = $this->posts_model->getPage($result["pages"]);
					} catch(\Exception $ex) {
						$result["error"]  = $ex->getMessage();
					}
				} else {
					$result["error"]  = $captchaValidationResult->message;
				}
			} else {
				$result["error"] = "Для выполненния запроса необходимо авторизоваться.";
			}
		} else {
			$result["error"] = "Некорректный метод запроса";
		}
		$result["csrf_hash"] =  $this->security->get_csrf_hash();
		$cap = $this->captcha_model->makeCAPTCHAEntry();
		$result["captcha_image"] = $cap["image"];
		$result["captcha_id"] = $cap["id"];
		echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}
}