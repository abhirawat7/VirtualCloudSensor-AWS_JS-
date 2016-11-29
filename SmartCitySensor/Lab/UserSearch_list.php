<?php
if(session_id() == "")
	session_start();
?>
<!doctype html>
<html>
<head>
</head>
<body>
<?php 
	include("Navigation.php");
	include ("Database.php");
?>

<?php
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		extract($_POST);

		$conn = getDatabaseConnection();
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
		else
		{    
			if($fname == "" && $lname=="" && $email=="" && $phone=="")
			{
				echo "Enter one Field";
				return;
			}

			$where = "";

			if($fname != "")
				$where .= "firstName = '$fname' ";

			if($lname != "")
			{
				if($where != "")
				{
					$where .= "AND ";					
				}
				$where .= "lastName = '$lname' ";
			}

			if($email != "")
			{
				if($where != "")
				{
					$where .= "AND ";					
				}
				$where .= "email = '$email' ";
			}

			if($phone != "")
			{
				if($where != "")
				{
					$where .= "AND ";					
				}

				$where .= "(mobileNumber = '$phone' OR phoneNumber = '$phone') ";
			}			

		    $sql = "Select * FROM userlist WHERE $where";
		    $result = $conn->query($sql);

		    if ($result->num_rows > 0)
		    {
?>		    	
				<table>
		    	<tr>
		    		<th>UserName</th>
		    		<th>Password</th>
		    		<th>First Name</th>
		    		<th>Last name</th>
		    		<th>E-mail</th>
		    		<th>Address</th>
		    		<th>Mobile Phone</th>
		    		<th>Home Phone</th>
		    	</tr>
<?php
		        while($row = $result->fetch_assoc()) 
		        {
?>
					<tr>
						<td><?php echo $row["userName"]; ?></td>
						<td><?php echo $row["password"]; ?></td>
						<td><?php echo $row["firstName"]; ?></td>
						<td><?php echo $row["lastName"]; ?></td>
						<td><?php echo $row["email"]; ?></td>
						<td><?php echo $row["address"]; ?></td>
						<td><?php echo $row["mobileNumber"]; ?></td>
						<td><?php echo $row["phoneNumber"]; ?></td>
					</tr>

<?php
		        }
		    }
		    else
		    {
		    	echo "No User found";
		    }
		}
	}
?>
</body>
</html>