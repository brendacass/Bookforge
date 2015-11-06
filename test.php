<?php
$path = $_SERVER['DOCUMENT_ROOT'] . "bookforge";

chdir($path);
define('DRUPAL_ROOT', getcwd()); //the most important line
require_once 'includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
?>
<style>

</style>

<div id="store_button">
	<a href="/store">Store</a>
	<div id="store_menu">

	</div>
</div>

