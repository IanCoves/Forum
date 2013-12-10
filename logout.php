<?php 
	session_start();
	session_destroy();
	
	//return to previous page.
	header('location: '. $_SERVER['HTTP_REFERER']);
?>