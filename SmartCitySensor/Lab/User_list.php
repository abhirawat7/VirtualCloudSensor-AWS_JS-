<!doctype html>
<html>
<head>
</head>
<boby>
<?php include("Navigation.php");?>

<section id="intro">
<h4>Logged in Successully!</h4></br></br>
<h4>All Resigtered User List:</h4>
<h6>Username	Password	Name    E-Mail</h6>
<pre>
<?php
include ("Database.php");
$conn = getDatabaseConnection();
if ($conn->connect_error) 
{
	die("Connection failed: " . $conn->connect_error);
}
else
{ 
	$sql = "SELECT * FROM userlist";
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
}

?>
</pre>
</section>
</boby>
</html>
