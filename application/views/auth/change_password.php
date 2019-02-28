<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы сброса пароля */
$this->view('include/header');
?>
<section id="intro" class="main">
<div id="errorMessage"><?php echo $message;?></div>
<?php echo form_open("auth/change_password");?>
<p>
	<?php echo lang('change_password_old_password_label', 'old_password', array("class" => "change-pwd-label"));?>
	<?php echo form_input($old_password,'', array("autocomplete" => "off"));?>
</p>
<p>
	<label class="change-pwd-label" for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label>
	<?php echo form_input($new_password,'', array("autocomplete" => "off"));?>
</p>
<p>
	<?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm', array("class" => "change-pwd-label"));?>
	<?php echo form_input($new_password_confirm, '', array("autocomplete" => "off"));?>
</p>
<?php echo form_input($user_id);?>
<p><?php echo form_submit('submit', lang('change_password_submit_btn'));?></p>
<?php echo form_close();?>
</section>
<?php
$this->view('include/footer');
?>
