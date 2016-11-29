<?php
	include("Navigation.php");
	$url = "http://fabposters.slashbin.in/users/list";  
	$ch = curl_init();   
	curl_setopt($ch, CURLOPT_URL, $url);  
	curl_setopt($ch, CURLOPT_HEADER, 0);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
	$output = curl_exec($ch);  
	curl_close($ch);  
	
	$users = json_decode($output, true);
	
	echo "<h4>Partner Sites Users:</h4>";
	
	foreach($users as $user) {
		echo $user['first_name'] . " " . $user['last_name'] . "<br/>";
	}
	
	echo "<h4>Home Site Users:</h4>";
	
	$users1 = fopen("user_list.txt", "r") or die("Unable to open file!");
	$users = json_decode(fread($users1,filesize("user_list.txt")), true);
	
	foreach($users as $user) {
		echo $user['firstName'] . " " . $user['lastName'] . "<br/>";
	}
?>