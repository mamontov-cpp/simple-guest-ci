<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы 404 */
$this->view('include/header');
?>
	<section id="intro" class="main">
		<p>Страница не найдена. Возможно она была удалена.</p>
		<ul class="actions">
			<li><a href="/" class="button big">Вернуться на главную?</a></li>
		</ul>
	</section>
<?php
$this->view('include/footer');
?>
