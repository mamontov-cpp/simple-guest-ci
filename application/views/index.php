<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы индекса */
$this->view('include/header');
?>
	<section id="intro" class="main">
		<p>Главная страница</p>
		<ul class="actions">
			<li><a href="/" class="button big">Добавить комментарий</a></li>
		</ul>
	</section>
<?php
$this->view('include/footer');
?>
