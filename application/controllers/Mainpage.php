<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Контроллер главной страницы
 * @class MainPage
 */
class Mainpage extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('security');

		$this->load->database();
	}
	/**
	 * Отображает главную страницу
	 */
	public function index()
	{
		$data['title'] = "Главная страница гостевой";
		$data['mainTitle'] = "GUEST BOOK";
		$data['auxTitle'] = "Посмотрите комментарии, или оставьте свой";

		$this->load->model("captcha_model");

		$this->load->model("posts_model");
		$this->captcha_model->deleteOldEntries();

		$this->load->view('index', $data);
	}
}
