<?php
	require 'db.php';
	unset($_SESSION['id']);
	unset($_SESSION['logged_user']);
	header('Location: /');
?>
