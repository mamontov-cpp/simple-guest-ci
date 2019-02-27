<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Редактирование пользователя */
$this->view('include/header');
?>
<section id="intro" class="main">
<div id="errorMessage"><?php echo $message;?></div>

<?php echo form_open(uri_string());?>
<p>
    <?php echo lang('edit_user_password_label', 'password', array("class" => "edit-user-label"));?>
    <?php echo form_input($password, '',  array("autocomplete" => "off"));?>
</p>
<p>
    <?php echo lang('edit_user_password_confirm_label', 'password_confirm', array("class" => "edit-user-label"));?>
    <?php echo form_input($password_confirm,'',  array("autocomplete" => "off"));?>
</p>
<?php if ($this->ion_auth->is_admin()): ?>
  <h3><?php echo lang('edit_user_groups_heading');?></h3>
  <?php foreach ($groups as $group):?>
      <label class="checkbox">
      <?php
          $gID=$group['id'];
          $checked = null;
          $item = null;
          foreach($currentGroups as $grp) {
              if ($gID == $grp->id) {
                  $checked= ' checked="checked"';
              break;
              }
          }
      ?>
      <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
      <?php echo $group['name'];?>
      </label>
  <?php endforeach?>
<?php endif ?>

<?php echo form_hidden('id', $user->id);?>
<?php echo form_hidden($csrf); ?>

<p class="save-btn-edit-wrapper"><?php echo form_submit('submit', lang('edit_user_submit_btn'));?></p>
<?php echo form_close();?>
</section>
<?php
$this->view('include/footer');
?>