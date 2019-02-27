<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @class Page404
 * Контроллер, ответственный за страницу 404
 */
class Page404 extends CI_Controller
{
	/**
	 * Отображает страницу 404
	 */
	public function index()
    {
        $data['title'] = "404 - страница не найдена";
        $data['mainTitle'] = "404";
        $data['auxTitle'] = "Страница не найдена";
        $this->output->set_status_header(404, "Not Found");
		$this->load->view('404', $data);
	}
}
