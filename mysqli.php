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

	//echo 'Success... ' . $db->host_info . "\n";
	//$db->close();
?>