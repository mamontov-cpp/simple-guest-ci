<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Авторизация и связанные с ней действия
 * @class Auth
 */
class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('security');

		$this->load->database();

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->helper('language');
	}

	/**
	 * Редирект на авторизацию, иначе если админ - показываем админку, иначе просто запись о том, что пользователь залогинен
	 */
	function index()
	{
		$this->data['title'] = "Авторизация";
		$this->data['mainTitle'] = "Auth";
		if (!$this->ion_auth->logged_in()) {
			// Редирект на логин
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			$this->data['auxTitle'] = "Авторизация";
			// Пользователю показывает обычную заглушку
			$this->_render_page('auth/index_simple_user', $this->data);
		} else {
			$this->data['auxTitle'] = "Данные пользователей";
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			// Показываем пользователей
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			$this->_render_page('auth/index', $this->data);
		}
	}

	/**
	 * Логин - не показываем если уже авторизован
	 */
	function login()
	{
		if ($this->ion_auth->logged_in()) {
			// Отправляем авторизованных на главную
			redirect('/');
			return;
		}
		$this->data['title'] = "Авторизация";
		$this->data['mainTitle'] = "Login";
		$this->data['auxTitle'] = "Авторизация";

		$this->form_validation->set_rules('identity', 'E-mail', 'required');
		$this->form_validation->set_rules('password', 'Пароль', 'required');

		if ($this->form_validation->run() == true) {
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('/', 'refresh');
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		} else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);

			$this->_render_page('auth/login', $this->data);
		}
	}

    /**
     * Выход из системы. редиректит на авторизацию
     */
	function logout()
	{
		$this->data['title'] = "Logout";

		$this->ion_auth->logout();

		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');
	}

	/**
	 * Смена пароля
	 */
	function change_password()
	{
		$this->data['title'] = "Авторизация";
		$this->data['mainTitle'] = "Change password";
		$this->data['auxTitle'] = "Смена пароля";

		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)  {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			$this->_render_page('auth/change_password', $this->data);
		} else {
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change) {
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Форма "Забыли пароль?"
	 */
	function forgot_password()
	{
		$this->data['title'] = "Авторизация";
		$this->data['mainTitle'] = "Forgot password";
		$this->data['auxTitle'] = "Забыли пароль?";


		$this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		if ($this->form_validation->run() == false) {
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			} else {
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		} else {
			// get identity from username or email
			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$identity = $this->ion_auth->where('username', strtolower($this->input->post('email')))->users()->row();
			} else {
				$identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
			}

			if (empty($identity)) {
				$this->ion_auth->set_message('forgot_password_email_not_found');
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/forgot_password", 'refresh');
			}

			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten) {
				//if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	/**
	 * Cброс пароля - финальный шаг в сбросе пароля
	 * @param null|string $code строка с данными
	 */
	public function reset_password($code = NULL)
	{
		$this->data['title'] = "Авторизация";
		$this->data['mainTitle'] = "Change password";
		$this->data['auxTitle'] = "Установка нового пароля";

		if (!$code) {
			$data['title'] = "404 - страница не найдена";
			$data['mainTitle'] = "404";
			$data['auxTitle'] = "Страница не найдена";
			$this->output->set_status_header(404, "Not Found");
			$this->load->view('404', $data);
		}

		$user = $this->ion_auth->forgotten_password_check($code);
		if ($user) {
			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false) {
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;
				$this->_render_page('auth/reset_password', $this->data);
			} else {
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {
					$this->ion_auth->clear_forgotten_password_code($code);
					show_error($this->lang->line('error_csrf'));
				} else {
					$identity = $user->{$this->config->item('identity', 'ion_auth')};
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));
					if ($change) {
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						$this->logout();
					} else {
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		} else {
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


	/**
	 * Активация пользователя
	 * @param int $id ID пользователя
	 */
	function activate($id)
	{
		// Активировать можно только админам
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
			$activation = $this->ion_auth->activate($id);
		}  else {
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}

		if ($activation) {
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		} else {
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Деактивация пользователя
	 * @param null|int $id ID пользователя
	 */
	function deactivate($id = NULL)
	{
		if (!($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) || !$id) {
			redirect("auth/forgot_password", 'refresh');
			return;
		}
		$this->data['title'] = "Деактивация";
		$this->data['mainTitle'] = "Deactivate user";
		$this->data['auxTitle'] = "Деактивация пользователя";

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('auth/deactivate_user', $this->data);
		} else {
			if ($this->input->post('confirm') == 'yes') {
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}

				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
					$this->ion_auth->deactivate($id);
				}
			}

			redirect('auth', 'refresh');
		}
	}

	/**
	 * Cоздание пользователя/Регистрация
	 */
	function create_user()
	{
		$this->data['title'] = "Регистрация";
		$this->data['mainTitle'] = "Register";
		$this->data['auxTitle'] = "Регистрация пользователя";

		if ($this->ion_auth->logged_in() && !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

        // Загрузка  капчи
        $this->load->model("captcha_model");

        $this->captcha_model->deleteOldEntries();

		$tables = $this->config->item('tables','ion_auth');

		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique['.$tables['users'].'.email]');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true) {
			$email    = strtolower($this->input->post('email'));
			$username = $email;
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('email'),
				'last_name'  => $this->input->post('email')
			);
		}

		// Проверка Капчи
        $captchaError = false;
		$captchaErrorMessage = "";
		if ($this->input->method(true) == "POST") {
            $captchaValidationResult = $this->captcha_model->checkCAPTCHA($this->input->post("captcha_id"), $this->input->post("captcha_word"));
            $captchaError = !($captchaValidationResult->ok);
            if ($captchaValidationResult->ok == false) {
                $captchaErrorMessage = "<p>" .  $captchaValidationResult->message ."</p>";
            }
        }

		if ($this->form_validation->run() == true && !$captchaError && $this->ion_auth->register($username, $password, $email, $additional_data)) {
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['message'] .= $captchaErrorMessage;

			$this->data['captcha'] = $this->captcha_model->makeCAPTCHAEntry();
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$this->_render_page('auth/create_user', $this->data);
		}
	}

	/**
	 * Синонимизация регистрации с созданием пользователя
	 */
	function register()
	{
		$this->create_user();
	}

	/**
	 * Редактирование пользования из админки - пока доступно только админу.
	 * Это немного порезанная версия, т.к. в основном она нужна для смены пароля.
	 * @param int $id ID пользователя
	 */
	function edit_user($id)
	{
		$this->data['title'] = "Редактировать пользователя";
		$this->data['mainTitle'] = "Edit user";
		$this->data['auxTitle'] = "Редактировать пользователя";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		$this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				show_error($this->lang->line('error_csrf'));
			}

			$data = array();

			if ($this->ion_auth->is_admin()) {
				$groupData = $this->input->post('groups');

				if (isset($groupData) && !empty($groupData)) {
					$this->ion_auth->remove_from_group('', $id);
					foreach ($groupData as $grp) {
						$this->ion_auth->add_to_group($grp, $id);
					}
				}
			}

			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE) {
				$this->ion_auth->update($user->id, $data);

				$this->session->set_flashdata('message', "<p class=\"ok\">Пользователь сохранён</p>");
				if ($this->ion_auth->is_admin()) {
					redirect('auth', 'refresh');
				} else {
					redirect('/', 'refresh');
				}
			}
		}

		$this->data['csrf'] = $this->_get_csrf_nonce();

		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->_render_page('auth/edit_user', $this->data);
	}

	/**
	 * Создание группы пользователей в админке
	 */
	function create_group()
	{
		$this->data['title'] = "Создание группы пользователей";
		$this->data['mainTitle'] = "Create user group";
		$this->data['auxTitle'] = "Создание группы пользователей";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

		if ($this->form_validation->run() == true) {
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id) {
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_group', $this->data);
		}
	}

	/**
	 * Редактирование группы пользователей
	 * @param int $id ID группы пользователей
	 */
	function edit_group($id)
	{
		$this->data['title'] = "Редактирование группы пользователей";
		$this->data['mainTitle'] = "Edit user group";
		$this->data['auxTitle'] = "Редактирование группы пользователей";
		if (!$id || empty($id)) {
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === true) {
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if ($group_update) {
					$this->session->set_flashdata('message', "<p class=\"ok\">" . $this->lang->line('edit_group_saved') . "</p>");
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->data['group'] = $group;

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('auth/edit_group', $this->data);
	}


	/**
	 * Получение nonce для защиты от CSRF
	 * @return array
	 */
	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	/**
	 * Проверка CSRF-токена на валидность
	 * @return bool
	 */
	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Вспомогательный рендеринг страницы, с возможностью вернуть результат вьюхи как строку
	 * @param $view
	 * @param null $data
	 * @param bool $render
	 * @return mixed
	 */
	function _render_page($view, $data = null, $render = false)
	{
		$this->viewdata = (empty($data)) ? $this->data: $data;
		$view_html = $this->load->view($view, $this->viewdata, $render);
		if (!$render) return $view_html;
	}

}
