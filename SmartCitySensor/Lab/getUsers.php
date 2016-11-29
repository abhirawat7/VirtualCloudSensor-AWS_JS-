<?php
	include("Navigation.php");
	echo "<h4>Home Site Users:</h4>";
	
	$users1 = fopen("user_list.txt", "r") or die("Unable to open file!");
	$users = json_decode(fread($users1,filesize("user_list.txt")), true);
	
	foreach($users as $user) {
		echo $user['firstName'] . " " . $user['lastName'] . "<br/>";
	}
?>