<?php
if(session_id() == "")
	session_start();
?>

<!doctype html?
<html>
<head>
<link rel="shortcut icon" href="favicon.ico">
</head>
<body>

<?php
	include ("Database.php");
	include("Navigation.php");
?>

<div class="container">
<div class="main">
	<!-- start registration -->
	<div class="registration">		
		<div class="registration_left">
		<h2>New User? <span>  Create an account</span></h2>
		<div class="registration_form">
			<form method="post" action="signUP.php">
					UserName: <input type="text" name="userName" style="background-color: white"></br>
					Password: <input type="password" name="password" style="background-color: white"></br>
					First Name: <input type="text" name="fName" required="required" style="background-color: white"/>
					last Name: <input type="text" name="lName" required="required" style="background-color: white"/>
					E-mail: <input type="email" name="EMail" required="required" style="background-color: white"/>
					Address: <input type="text" name="Address" required="required" style="background-color: white"/></br>
					Mobile Phone: </br><input type="number" name="mPhone" maxlength = 10 required="required" /></br></br>
					Home Phone: </br><input type="number" name="hPhone" maxlength = 10 required="required" /></br></br>
					<input type="submit" value="Submit"/>
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
	$conn = getDatabaseConnection();
	if ($conn->connect_error) 
	{
    	die("Connection failed: " . $conn->connect_error);
    }
    else
	{    
	    $sql = "INSERT INTO userlist (userName, password, firstName, lastName, email, address, mobileNumber, phoneNumber) 
	    		VALUES('$userName', '$password', '$fName', '$lName', '$EMail', '$Address', '$mPhone', '$hPhone')";

	    if($conn->query($sql) === TRUE)
	    {
	    	echo "Registration successful!";
			
			$sql = "SELECT * FROM userlist";
			$result = $conn->query($sql);	
			
			$Contacts = fopen("user_list.txt", "w") or die("Unable to open file!");
			
			if ($result->num_rows > 0)
			{
				$a = array();
				while($row = $result->fetch_assoc())
				{
					array_push($a, $row);					
				}
				fwrite($Contacts, json_encode($a));
			}    	
	    }
	    else
	    {
	    	echo $conn->error;
	    	echo "Failed";
	    }
	}	
}


?>

</body>
</html>