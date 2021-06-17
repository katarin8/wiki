<?php
	require "../libs/rb.php";
    $connection = mysqli_connect('127.0.0.1', 'f0539094_wiki', '1234', 'f0539094_wiki');
    R::setup ('mysql:host=localhost;dbname=f0539094_wiki',
		        'mysql','mysql');
    @session_start();
?>
