<?php 
	session_start();
	unset($_SESSION['user']);
	unset($_SESSION['user_id']);
	unset($_SESSION['user_admin']);
	
	//session_destroy();
	
	//return to previous page.
	header('location: '. $_SERVER['HTTP_REFERER']);
?>