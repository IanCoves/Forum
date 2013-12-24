<!--Rowan Turner-->

<html>

	<head>
		<title>Topic Menu</title>
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
			echo "</br>";
			//Display admin priveleges from session
			if (isset($_SESSION['user_admin'])) {
				if($_SESSION['user_admin'] ==0) {
					echo "Moderator";
				}
				else {
					if ($_SESSION['user_admin'] ==1) {
						echo "<a href='./admin.php'>Admin</a>";
					}
				}
			}
	?>
		
		<!--Lougout Form/ button-->
		<form id="logout" method="post" action="logout.php">
			<input type="submit" name="logout" id="logout-button" value="logout" />
		</form>
		
	<?php
			//submit topic to database
			if($_POST['send-topic']) {	
			
				
				//Makes sure they did not leave blank fields
				if(!$_POST['topic']) 
				{
					?>
					<script type='text/javascript'>alert('ERROR: Empty topic subject.');</script>
					<?php
				}
				//To protect against basic SQL injection
				$topic = stripslashes($_POST['topic']);
				$topic = mysql_real_escape_string($topic);
				
				//Insert new reply into the database
				$user_id = $_SESSION['user_id'];
				$insert = "INSERT INTO topic (`topic_subject`, `topic_user`) VALUES('$topic', '$user_id')";
				$add_topic = mysqli_query($db, $insert);
				if($add_topic) {
		?>
		
		<p>Topic Created!.</p>
			
		<?php
				}
			}
			
			
		
			//*** DELETE TOPIC *** ***
		//topic id sent from form
		if ($_POST['delete_topic']) {
			$id = $_POST['topic_id'];

			//Run delete query with topic ID
			$delTopic="DELETE FROM topic WHERE topic_id='$id'";
			$delReply="DELETE FROM reply WHERE reply_topic='$id'";
			mysqli_query($db, $delReply);
			mysqli_query($db, $delTopic);
					
		}
		//*** *** *** *** *** *** ***
		/*
		if(($_POST['edit_topic']) && ($_POST['edited_topic'])) {
		
			//strip content to prevent basic SQL injection
			$topic = stripslashes($_POST['edited_topic']);
			$topic = mysql_real_escape_string($topic);
			
			//save edited topic
			$sql="UPDATE topic SET topic_subject='$topic' WHERE topic_id='".$_POST['topic_id']."'";
			if($result=mysqli_query($db, $sql)) {
				//display new topic
				echo "<p>topic saved.</p>";
			}
			else {
				echo "<p>Edit topic failed.</p>";
			}
		}*/
		?>
			
		<h3>Add a topic</h3>
		<!--Add topic to database form-->
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >
			<input type="text" name="topic" size="50"/>
			</br>
			<input type="submit" name="send-topic" value="Submit Topic"/>
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
		
			echo "You're not currently logged in.<br>Only registered users can create a topic.</br></br>";
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
		
		//select all rows from topic table
		$sql = "SELECT * FROM topic";
		if ($result = mysqli_query($db, $sql)) {
		
				
	?>
		<h4>Topic List.</h4>
		<p>Click a topic button to add and view replies.</p>
		
	<?php
		
			//fetch table object array 
			while ($row = mysqli_fetch_row($result)) {
				//Add form with input button for each topic
				echo '<form id="select_topic" method="post" action="reply.php">';
				echo '<input type="submit" name="topic_subject" id="topic-button" value="'.$row[1].'">';
				echo '<input type="hidden" name="topic_id" value="'.$row[0].'">';
				echo "</form>";
				
				//if user has admin, display edit form.
				if (isset($_SESSION['user'])) {
					if(($_SESSION['user_admin']) == 1){
						
						//start edit form
						echo '<form id="change_topic" method="post" action="'.$_SERVER['PHP_SELF'].'">';
						
						//edit button clicked on this reply, but has not been edited yet.
						if (($_POST['edit_topic']) && ($_POST['topic_id'] == $row[0]) && !($_POST['edited_topic'])) {

							//display editable text box with old topic as existing text
							echo '<input type="text" name="topic" size="50" value="'.$row[1].'"/></br>';
						}
				
						echo '<input type="submit" name="delete_topic" id="delete-button" value="Delete" />';
						echo '<input type="submit" name="edit_topic" id="edit-button" value="Edit" />';
						echo '<input type="hidden" name="topic_id" value="'.$row[0].'" /></form>';
						echo "</br>";
					}
				}
			}
	?>		
		
	<?php
		}
		else {
			echo "Unable to find a topic.";
		}
		
		$db->close();
	?>
	</body>
	
</html>