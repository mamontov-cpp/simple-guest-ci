<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы логина */
$this->view('include/header');
?>
<section id="intro" class="main">
<div id="errorMessage"><?php echo $message;?></div>

<?php echo form_open("auth/login");?>

  <p>
    <?php echo lang('login_identity_label', 'identity', array("class" => "login-label"));?>
    <?php echo form_input($identity);?>
  </p>

  <p>
    <?php echo lang('login_password_label', 'password', array("class" => "login-label"));?>
    <?php echo form_input($password, '', array("autocomplete" => "off"));?>
  </p>

  <p>
    <?php echo lang('login_remember_label', 'remember');?>
    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
  </p>


  <p><?php echo form_submit('submit', "Войти");?></p>

<?php echo form_close();?>

<p><a href="/auth/forgot_password">Забыли пароль?</a>&nbsp;|&nbsp;<a href="/auth/register">Регистрация</a></p>
</section>
<?php
$this->view('include/footer');
?>