<!--Rowan Turner-->

<html>

	<head>
		<title>Register Account</title>
	</head>
	
	<body>
	<?php 
		session_start(); 
		
		//connect to the database
		require("mysqli.php");

		if($_POST['register']) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			
			//check for blank feilds
			if(!$_POST['username'] | !$_POST['password']) {
			?>
				<script type='text/javascript'>alert('ERROR: Not all required fields are complete.');</script>
			<?php
				die('You did not complete all of the required fields.<br><a href="register.php">Try again</a>');
			}
			
			//Check to see if username already exists.
			$sql = "SELECT user_name FROM user WHERE `user_name`='$username'";
			if($result = mysqli_query($db, $sql)) {
				$count = mysqli_num_rows($result);
				
				if($count == 0) {
					$insert = "INSERT INTO user (`user_name`, `user_pass`) VALUES('$username', '$password')";
					$add_user = mysqli_query($db, $insert);
					
					//return to topic.php
					header('location: topic.php');
				}
				else {
					echo "Registration failed: </br>Username already exists. Please register a different name.</br></br>";
					
				}
			}
		}		
	?>
		<h4>Creating an account.</h4>
		<p>Register desired username and password: </p>
		<!--Register form-->
		<form id="register" method="post" action="register.php">
			<label> Username:
				<input type="text" name="username" id="username" size="15" />
			</label>
			<label> Password:
				<input type="password" name="password" id="password" size="15" />
			</label>
			</label>
			<input type="submit" name="register" id="register-button" value="register">
		</form>
		</br>
		<a href='./topic.php'>Back to topic menu.</a>
	<?php
		$db->close();
	?>
	</body>
	
</html>