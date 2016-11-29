<!doctype html?
<html>
<head>
<link rel="shortcut icon" href="favicon.ico">
</head>
<body>

<?php
	include("Navigation.php");
	include ("Database.php");
?>

<div class="container">
<div class="main">
	<!-- start registration -->
	<div class="registration">		
		<div class="registration_left">
		<h2>Returning user? <span>  login</span></h2>
		<div class="registration_form">
			<form method="post" action="Login.php">
			UserName: <input type="text" name="userName" required="required"/></br>
			Password: <input type="password" name="password" required="required"/></br>
			<input type="submit" value="Login" name="Login"/>
			</form>
		</div>
		</div>
	</div>
</div>
</div>


<?php 

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	extract($_POST); 

	if(isset($Login))
	{
		$conn = getDatabaseConnection();
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
		else
		{
			$sql = "Select * FROM userlist";
		    $result = $conn->query($sql);

		    if ($result->num_rows > 0)
		    {
		    	while($row = $result->fetch_assoc()) 
        		{
        			if(strcmp($userName, $row["userName"]) == 0  && strcmp($password, $row["password"]) == 0)
					{				
						session_start();			
						$_SESSION["CurrentUser"] = $userName;	
						header("Location: User_list.php");	
						break;
					}
        		}
		    }
		}
		
		if (session_id() == "")
		{
			echo " Wrong UserName or Password";
		}
	}	
}
?>

</body>
</html>