<?php
if(session_id() == "")
	session_start();
?>
<!doctype html>
<html>
<head>
</head>
<body>
<?php include("Navigation.php");?>

<div class="container">
<div class="main">
	<!-- start registration -->
	<div class="registration">		
		<div class="registration_left">
		<h2>Searching User? <span>  Enter any Detail Here</span></h2>
		<div class="registration_form">
			<form action="UserSearch_list.php" method="post">
				<div>
					<label>
						<input placeholder="First Name" type="text" name="fname"/></br>
					</label>
				</div>
				<div>
					<label>
						<input placeholder="Last Name" type="text" name="lname"/></br>
					</label>
				</div>
				<div>
					<label>
						<input placeholder="E-Mail" type="email" name="email"/></br>
					</label>
				</div>
				<div>
					<label>
						<input placeholder="Phone Number" type="number" name="phone" maxlength = 10/></br>
					</label>
				</div>	
				<input type="submit" value="Search"/>
			</form>		
		</div>
		</div>
	</div>
</div>
</div>

</body>
</html>