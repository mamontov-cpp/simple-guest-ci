<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы регистрации, создания пользователя админом.
   Исходный шаблон порезан для упрощения процесса.
 */
$this->view('include/header');
?>
<section id="intro" class="main">
<div id="errorMessage"><?php echo $message;?></div>

<?php echo form_open("auth/create_user");?>
<p>
    <?php echo lang('create_user_email_label', 'email', array("class" => "register-label"));?>
    <?php echo form_input($email);?>
</p>
<p>
    <?php echo lang('create_user_password_label', 'password', array("class" => "register-label"));?>
    <?php echo form_input($password,'',  array("autocomplete" => "off"));?>
</p>
<p>
    <?php echo lang('create_user_password_confirm_label', 'password_confirm', array("class" => "register-label"));?>
    <?php echo form_input($password_confirm,'',  array("autocomplete" => "off"));?>
</p>
<p>Введите символы на картинке:</p>
<p>
    <?=$captcha['image']?>
</p>
<p>
    <input type="text" name="captcha_word" autocomplete="off">
</p>
<p>
    <input type="hidden" name="captcha_id" value="<?=$captcha["id"]?>" >
</p>
<p><?php echo form_submit('submit', "Регистрация");?></p>
<?php echo form_close();?>
</section>
<?php
$this->view('include/footer');
?>

