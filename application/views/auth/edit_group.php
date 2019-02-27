<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы редактирования пользователей
 */
$this->view('include/header');
?>
<section id="intro" class="main">
<div id="errorMessage"><?php echo $message;?></div>
<?php echo form_open(current_url());?>
<p>
    <?php echo lang('edit_group_name_label', 'group_name', array("class" => "group-edit-label"));?>
    <?php echo form_input($group_name);?>
</p>
<p>
    <?php echo lang('edit_group_desc_label', 'description', array("class" => "group-edit-label"));?>
    <?php echo form_input($group_description);?>
</p>
<p><?php echo form_submit('submit', lang('edit_group_submit_btn'));?></p>
<?php echo form_close();?>
</section>
<?php
$this->view('include/footer');
?>
