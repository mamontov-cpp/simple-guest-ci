<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* Шаблон  для футера страницы */
$addScripts = true;
if (isset($noScriptsInFooter)) {
    $addScripts = false;
}
?>
<footer id="footer">
    <p class="copyright">&copy; 2019. Unknown</p>
</footer>
</div>
<?php if ($addScripts):?>
    <script src="/assets/js/jquery.min.js"></script>
	<script src="/assets/js/skel.min.js"></script>
	<script src="/assets/js/main.js"></script>
<?php endif;?>
</body>
</html>
