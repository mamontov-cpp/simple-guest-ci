<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для страницы индекса авторизации */
$this->view('include/header');
?>
<section id="intro" class="main">
	<p>Вы уже авторизованы в системе.</p>
	<ul class="actions">
		<li><a href="/" class="button big">Вернуться на главную?</a></li>
	</ul>
</section>
<?php
$this->view('include/footer');
?>