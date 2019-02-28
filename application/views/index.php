<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы индекса */
$this->view('include/header');
?>
<div class="content-wrapper">
</div>
<section class="pagination-container">
	<div class="pagination">
		<div class="pagination-row">
		</div>
	</div>
</section>
<section class="large-block main">
	<?php if ($this->ion_auth->logged_in()):?>
		<div id="errorMessage"></div>
		<?php echo form_open("auth/change_password", array("id" => "add_post"));?>
			<p>
				Текст: <br/>
				<textarea name="text"></textarea>
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
			<p><?php echo form_submit('submit', "Отправить", array("id" => "send-new-post"));?></p>
		<?php echo form_close(); ?>
	<?php else:?>
		<p><a href="/auth/login/">Авторизуйтесь</a> или <a href="/auth/register">Зарегистрируйтесь</a>, чтобы оставлять комментарии.</p>
	<?php endif;?>
</section>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/skel.min.js"></script>
<script src="/assets/js/main.js"></script>
<script type="text/javascript">
	showPagination(<?php echo (string)$pagesCount?>, <?php echo (string)$currentPage?>);
	showPage(<?php echo json_encode($page, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)?>);
	window["csrf_hash"] = "<?php echo $csrf_hash?>";
	window["userId"] = "<?php echo $userId?>";
</script>
<?php
$this->view('include/footer');
?>
