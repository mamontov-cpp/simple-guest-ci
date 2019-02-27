<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы "Забыли пароль?" */
$this->view('include/header');
?>
<section id="intro" class="main">
<div id="errorMessage"><?php echo $message;?></div>

<?php echo form_open("auth/forgot_password");?>

      <p>
      	<label for="email"><?php echo sprintf(lang('forgot_password_email_label'), $identity_label);?></label> <br />
      	<?php echo form_input($email);?>
      </p>

      <p><?php echo form_submit('submit', lang('forgot_password_submit_btn'));?></p>

<?php echo form_close();?>
</section>
<?php
$this->view('include/footer');
?>
