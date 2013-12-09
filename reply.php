<!--Rowan Turner-->

<html>

	<head>
		<title>Reply</title>
	</head>
	
	<body>
	<?php


	class forum_mysqli extends mysqli {
		public function __construct($host, $user, $pass, $db) {
			parent::__construct($host, $user, $pass, $db);

			if (mysqli_connect_error()) {
				die('Connect Error (' . mysqli_connect_errno() . ') '
						. mysqli_connect_error());
			}
		}
	}

	$db = new forum_mysqli('localhost', 'root', 'hungry', 'forumDB');

	/*
	echo 'Success... ' . $db->host_info . "\n";
	*/
	?>
		
		
		
		
	<?php
		
		//Table name for replies
		$replyTbl = "reply";
		
		//submit reply button pressed
		if($_POST['send-reply']) 
		{	
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
			$insert = "INSERT INTO reply (`reply_content`) VALUES('$reply')";
			$add_reply = mysqli_query($db, $insert);
			if($add_reply)
			{
	?>
	
	<p>Reply Successful!.</p>
		
	<?php
			}
		}
	?>
		
		<h3>Hey you!</br>Say Something!</h3>
		<!--Add reply form-->
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >
			<textarea name="reply" rows="14" cols="80"></textarea>
			<input type="submit" name="send-reply" value="Submit">
		</form>
		
		<h3>What others had to say:</h3>
		</br>
	<?php
		//select data from "reply_content"
		$sql = "SELECT reply_content FROM reply";
		if ($result = mysqli_query($db, $sql)) {
		
			//fetch object array 
			while ($row = mysqli_fetch_row($result)) {
				echo $row[0]."</br></br>";
			}
			/* free result set */
			mysqli_free_result($result);
		}
		$db->close();
	?>
	</body>
	
</html>