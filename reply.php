<!--Rowan Turner-->
	
<html>

	<head>
		<title>Reply</title>
	</head>
	
	<body>
	<?php 
		/**/		
		session_start(); 
		
		//connect to the database
		require("mysqli.php");
		
		//check topic
		if($_POST['topic_subject']) {
			$_SESSION['topic_subject'] = $_POST['topic_subject'];
			$_SESSION['topic_id'] = $_POST['topic_id'];
		}
		$topic_id = $_SESSION['topic_id'];

		//Check Login
		if(isset($_SESSION['user'])) {
			//logged in
			
			echo "Logged in as ";
			echo $_SESSION['user'];
			echo "</br>";
			//Display admin priveleges from session
			if (isset($_SESSION['user_admin'])) {
				if($_SESSION['user_admin'] ==0) {
					echo "Moderator";
				}
				else {
					if ($_SESSION['user_admin'] ==1) {
						echo "Admin";
					}
				}
			}
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
			echo "Not registered? <a href=\"register.php\">Create a free account</a>.</br></br>";
		
		}
		/**/
		
	
	?>
		<a href='./topic.php'>Return to Topic Menu.</a>
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
				$insert = "INSERT INTO reply (`reply_content`, `reply_user`, `reply_topic`) VALUES('$reply', '$user_id', '$topic_id')";
				$add_reply = mysqli_query($db, $insert);
			}
			else {
				$insert = "INSERT INTO reply (`reply_content`, `reply_topic`) VALUES('$reply', '$topic_id')";
				$add_reply = mysqli_query($db, $insert);
			}
			if($add_reply) {
	?>
	
	<p>Reply Saved.</p>
		
	<?php
			}
		}
		//*** DELETE REPLY *** ***
		//reply id sent from form
		if ($_POST['delete_reply']) {
			$id = $_POST['reply_id'];

			//Run delete query with reply ID
			$sql="DELETE FROM reply WHERE reply_id='$id'";
			if($result=mysqli_query($db, $sql)) {
				
				echo "Reply deleted.";
			}
			else {
				echo "Reply failed to delete.";
			}
		}
		//*** *** *** *** *** *** ***
		
		if(($_POST['edit_reply']) && ($_POST['edited_reply'])) {
		
			//strip content to prevent basic SQL injection
			$reply = stripslashes($_POST['edited_reply']);
			$reply = mysql_real_escape_string($reply);
			
			//save edited reply
			$sql="UPDATE reply SET reply_content='$reply' WHERE reply_id='".$_POST['reply_id']."'";
			if($result=mysqli_query($db, $sql)) {
				//display new reply
				echo "<p>Reply saved.</p>";
			}
			else {
				echo "<p>Edit reply failed.</p>";
			}
		}
		
		
		//select user id from topic table
		$sql = "SELECT topic_user FROM topic WHERE topic_id = $topic_id";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_row($result);
		
		//select topic creators name from user
		$sql = "SELECT user_name FROM user WHERE user_id = $row[0]";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_row($result);
		
		
		//<!--Topic Title-->
		echo "<h3>".$_SESSION['topic_subject']."</h3>";
		echo "Topic started by ".$row[0];
		
	?>
		
		
		<!--Add reply to database form-->
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >
			<textarea name="reply" rows="4" cols="60"></textarea>
			</br>
			<input type="submit" name="send-reply" value="Submit">
		</form>
		
		<!--Topic Replies from database Title-->
		<h3>What others had to say:</h3>
	<?php
		//select content from "reply_content" and id from "reply_user" where "reply_topic" is the topic id
		$sql = "SELECT reply_content, reply_user, reply_id FROM reply WHERE reply_topic = $topic_id";
		if ($result = mysqli_query($db, $sql)) {
		
			//fetch reply object array 
			while ($row = mysqli_fetch_row($result)) {
			
				//If reply is made by a known user
				if(!$row[1]==NULL) {
				
					//retreive user_name from user
					$findByID = "SELECT user_name FROM user WHERE user_id='$row[1]'";
					$resultByID = mysqli_query($db, $findByID);
					$nameByID = mysqli_fetch_row($resultByID);
					
					//display user name
					echo $nameByID[0]." said: </br>";
				}
				else {
					//display "guest" user
					echo "Guest said: </br>";
				}
				echo $row[0];
	
				if (isset($_SESSION['user'])) {
					
					//if reply was made by the current registered user, or loged in ueser has admin
					if(($row[1] == $_SESSION['user_id']) || (($_SESSION['user_admin']) != NULL)){
						
						//start edit form
						echo '<form id="change_reply" method="post" action="'.$_SERVER['PHP_SELF'].'">';
						
						//edit button clicked on this reply, but has not been edited yet.
						if (($_POST['edit_reply']) && ($_POST['reply_id'] == $row[2]) && !($_POST['edited_reply'])) {

							//display editable text box with old reply as existing text
							echo '<textarea name="edited_reply" rows="4" cols="60" >'.$row[0].'</textarea></br>';
						}
				
						echo '<input type="submit" name="delete_reply" id="delete-button" value="Delete" />';
						echo '<input type="submit" name="edit_reply" id="edit-button" value="Edit" />';
						echo '<input type="hidden" name="reply_id" value="'.$row[2].'" /></form>';
						echo "</br>";
					}
				}
				else {
					echo '</br></br>';
				}
					
					
				
			}
			/* free result set */
			mysqli_free_result($result);
		}
		$db->close();
	?>
	</body>
	
</html>