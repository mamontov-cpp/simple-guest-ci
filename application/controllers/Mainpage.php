<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Контроллер главной страницы
 * @class MainPage
 */
class Mainpage extends CI_Controller
{
	/**
	 * Отображает главную страницу
	 */
	public function index()
	{
		$data['title'] = "Главная страница гостевой";
		$data['mainTitle'] = "GUEST BOOK";
		$data['auxTitle'] = "Посмотрите комментарии, или оставьте свой";
		$this->load->view('index', $data);
	}
}
