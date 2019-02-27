<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Создание группы */
$this->view('include/header');
?>
<section id="intro" class="main">
<div id="errorMessage"><?php echo $message;?></div>

<?php echo form_open("auth/create_group");?>
<p>
	<?php echo lang('create_group_name_label', 'group_name', array("class" => "group-edit-label"));?>
	<?php echo form_input($group_name);?>
</p>
<p>
	<?php echo lang('create_group_desc_label', 'description', array("class" => "group-edit-label"));?>
	<?php echo form_input($description);?>
</p>
<p><?php echo form_submit('submit', lang('create_group_submit_btn'));?></p>
<?php echo form_close();?>
</section>
<?php
$this->view('include/footer');
?>