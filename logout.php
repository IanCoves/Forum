<?php 
	session_start();
	$_SESSION['user'] = NULL;
	
	//session_destroy();
	
	//return to previous page.
	header('location: '. $_SERVER['HTTP_REFERER']);
?>