<!--Rowan Turner-->

<html>

	<head>
		<title>Reply</title>
	</head>
	
	<body>
	<?php 
		session_start(); 
		
		//connect to the database
		require("mysqli.php");

		//Check Login
		if(isset($_SESSION['user'])) {
			//logged in
			
			echo "Logged in as ";
			echo $_SESSION['user'];
			
	?>
		
		<!--Lougout Form/ button-->
		<form id="logout" method="post" action="logout.php">
			<input type="submit" name="logout" id="logout-button" value="logout" />
		</form>
		
	<?php
		}
		else {
		//not logged in
			//check for login fail msg
			if(isset($_SESSION['loginFailMsg'])){
				echo $_SESSION['loginFailMsg']."</br>";
				unset($_SESSION['loginFailMsg']);
			}
		
			echo "You're not currently logged in.<br>";
	?>
	
	
		<!--Login form-->
		<form id="login" method="post" action="checkLogin.php">
			<label> Username:
				<input type="text" name="username" id="username" size="15" />
			</label>
			<label> Password:
				<input type="password" name="password" id="password" size="15" />
			</label>
			<input type="submit" name="login" id="login-button" value="Login" />
		</form>
		
	<?php
			echo "Not registered? <a href=\"register.php\">Create a free account</a>.";
		
		}
		/**/
		
	
	?>
	<?php		
		//submit reply to database
		if($_POST['send-reply']) {	
			//Makes sure they did not leave blank fields
			if(!$_POST['reply']) 
			{
				?>
				<script type='text/javascript'>alert('ERROR: Empty reply.');</script>
				<?php
			}
			//To protect against basic SQL injection
			$reply = stripslashes($_POST['reply']);
			$reply = mysql_real_escape_string($reply);
			
			//Insert new reply into the database
			if(isset($_SESSION['user'])){
				$user_id = $_SESSION['user_id'];
				$insert = "INSERT INTO reply (`reply_content`, `reply_user`) VALUES('$reply', '$user_id')";
				$add_reply = mysqli_query($db, $insert);
			}
			else {
				$insert = "INSERT INTO reply (`reply_content`) VALUES('$reply')";
				$add_reply = mysqli_query($db, $insert);
			}
			if($add_reply) {
	?>
	
	<p>Reply Successful!.</p>
		
	<?php
			}
		}
	?>
		<!--Topic Title-->
		<h3>Free Chat Topic!</h3>
		
		
		<!--Add reply to database form-->
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >
			<textarea name="reply" rows="14" cols="80"></textarea>
			</br>
			<input type="submit" name="send-reply" value="Submit">
		</form>
		
		<!--Topic Replies from database Title-->
		<h3>What others had to say:</h3>
	<?php
		//select content from "reply_content" and id from "reply_user"
		$sql = "SELECT reply_content, reply_user FROM reply";
		if ($result = mysqli_query($db, $sql)) {
		
			//fetch reply object array 
			while ($row = mysqli_fetch_row($result)) {
			
				//check if reply was made by a known user
				if(!$row[1]==NULL) {
				
					//retreive user_name from user
					$findByID = "SELECT user_name FROM user WHERE user_id='$row[1]'";
					$resultByID = mysqli_query($db, $findByID);
					$nameByID = mysqli_fetch_row($resultByID);
					
					//display user name and reply content
					echo $nameByID[0]." said: </br>";
					echo $row[0]."</br></br>";
				}
				else {
					//display reply content under default "guest" user
					echo "Guest said: </br>";
					echo "  ".$row[0]."</br></br>";
				}
			}
			/* free result set */
			mysqli_free_result($result);
		}
		$db->close();
	?>
	</body>
	
</html>