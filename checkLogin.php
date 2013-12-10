
<?php
	session_start();
	
	//connect to the database
	require("mysqli.php");
	
	//Table name for user data
	$tblName = "user";
	
	//Username & passwoed sent from form
	$user = $_POST['username'];
	$password = $_POST['password'];

	//To protect MySQL injection
	$user = stripslashes($user);	//Escapes special characters
	$password = stripslashes($password);
	$user = mysql_real_escape_string($user);	//Un-quotes a quoted string
	$password = mysql_real_escape_string($password);

	//Run select query to determine if user and password match a user row
	$sql="SELECT * FROM $tblName WHERE user_name='$user' and user_pass='$password'";
	$result=mysqli_query($db, $sql);
	
	//Count table rows returned
	$count=mysqli_num_rows($result);
	
	
	//If result matched $user and $password, table row must be 1 row
	if($count==1)
	{
		//Retreive user data
		$row = mysqli_fetch_row($result);
		
		//Register user's name and id
		$_SESSION['user'] = $user;
		$_SESSION['user_id'] = $row[0];
	}
	else
	{
		$loginFailMsg = "Username or Password did not match a registered user.";
		$_SESSION["loginFailMsg"] = $loginFailMsg;
	}
	//return to previous page.
	header('location: '. $_SERVER['HTTP_REFERER']);
?>