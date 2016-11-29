<?php
if(session_id() == "")
	session_start();	
?>
<!doctype html?
<html>
<head>
<!--link rel="stylesheet" href="assets/css/main.css" /-->
<link rel="stylesheet" href="web/css/bootstrap.css">
<script type='text/javascript' src="web/js/jquery-1.11.1.min.js"></script>
<link href="web/css/style.css" rel='stylesheet' type='text/css' />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,700,900' rel='stylesheet' type='text/css'>
<!-- start menu -->
<link href="web/css/megamenu.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="js/megamenu.js"></script>
<script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
<script src="web/js/menu_jquery.js"></script>
<script src="web/js/simpleCart.min.js"> </script>
<link rel="shortcut icon" href="favicon.ico">

<style>

#cloth {
    float:left;
    top: 0px;
    left: 170px;
    width:160px;
    height:400px;
    margin:20px;
}

#clothbar {
    top: 0px;
    left: 170px;
    width:100%;
    height:200%;
    margin:5px;    
}

</style>

</head>
<body>
<?php

if (isset($_SESSION["CurrentUser"]) && $_SESSION["CurrentUser"] != "")
{	
	$login = "Welcome, ".$_SESSION["CurrentUser"];
}
else
	$login = "Login";
?>
<div class="top_bg">
	<div class="container">
		<div class="header_top">
			<div class="top_right">
				<ul>					
					<li><a href="Contacts.php">Contact</a></li>|
					<li><a href="searchUser.php">Search User</a></li>
				</ul>
			</div>
			<div class="top_left">
				<h2><span></span> Call us : 408 507 4028</h2>
			</div>
				<div class="clearfix"> </div>
		</div>
	</div>
</div>

<div class="header_bg">
<div class="container">
	<div class="header">
	<div class="head-t">
		<div class="logo">
			<a href="index.php"><img src="web/images/logo1.png" class="img-responsive" alt=""/> </a>
		</div>
		<div class="header_right">
			<div class="rgt-bottom">
				<div class="log">
					<div class="login" >
						<div id="loginContainer">
						<!--a href="login.php" id="loginButton"><span>Login</span></a-->
						<?php
							if (isset($_SESSION["CurrentUser"]) && $_SESSION["CurrentUser"] != "")
							{
						?>
								<a href="Logout.php"><?php echo $login;?></a></li>
						<?php
							}
							else
							{
						?>
								<a href="Login.php"><span><?php echo $login;?></span></a></li>
						<?php
							}

						?>
						</div>
					</div>
				</div>
				<div class="reg">
					<a href="signUP.php">REGISTER</a>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
</div>

<ul class="megamenu skyblue">
			<li class="active grid"><a class="color1" href="index.php">OneStopShop</a></li>
			<li class="grid"><a class="color2" href="product.php">Products</a></li>
			<li class="grid"><a class="color3" href="News.php">News</a></li>
			<li class="grid"><a class="color4" href="About.php">About</a></li>	
			<li class="grid"><a class="color4" href="getUsers.php">Local Users</a></li>			
			<li class="grid"><a class="color4" href="getAllUserName.php">All Users</a></li>
			<li class="grid"><a class="color7" href="index.php#RecentVisited">Recent Visited Product</a></li>
			<li class="grid"><a class="color8" href="index.php#MostPopular">Popular Product</a></li>

</ul>

</header>
</body>
</html>