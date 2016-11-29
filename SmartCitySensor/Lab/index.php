<?php
if(session_id() == "")
	session_start();	
?>
<!doctype html?
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
<?php
include("Navigation.php");
?>



<div class="clearfix"> </div>
<div class="clearfix"> </div>

<div class="arriv">
	<div class="container">
		<div class="arriv-top">
			<div class="col-md-6 arriv-left">
				<img src="web/images/Books.jpg" class="img-responsive" alt="">
				<div class="arriv-info">
					<h3>Books</h3>
					<p>Pick a your Story to Read</p>
					<div class="crt-btn">
						<a href="Books.php">Shop Now</a>
					</div>
				</div>
			</div>
			<div class="col-md-6 arriv-right">
				<img src="web/images/2.jpg" class="img-responsive" alt="">
				<div class="arriv-info">
					<h3>Clothes</h3>
					<p>REVIVE YOUR WARDROBE WITH CHIC KNITS</p>
					<div class="crt-btn">
						<a href="Clothes.php">SHOP NOW</a>
					</div>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>
		<div class="arriv-bottm">
			<div class="col-md-8 arriv-left1">
				<img src="web/images/3.jpg" class="img-responsive" alt="">
				<div class="arriv-info1">
					<h3>Music</h3>
					<p>Listen to Silence</p>
					<div class="crt-btn">
						<a href="Music.php">SHOP NOW</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 arriv-right1">
				<img src="web/images/4.jpg" class="img-responsive" alt="">
				<div class="arriv-info2">
					<a href="Accessories.php"><h3>Accessories<i class="ars"></i></h3></a>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>		
	</div>
</div>

<?php
if (isset($_SESSION["CurrentUser"]) && $_SESSION["CurrentUser"] != "")
{
	if(isset($_COOKIE[$_SESSION["CurrentUser"]."recentVisitedSite"]))
	{
		$RecentSite = unserialize($_COOKIE[$_SESSION["CurrentUser"]."recentVisitedSite"]);
	}
	if(isset($_COOKIE[$_SESSION["CurrentUser"]."mostVisitedSite"]))
	{
		$MostVisitedSite = unserialize($_COOKIE[$_SESSION["CurrentUser"]."mostVisitedSite"]);		
	}
}
else
{
	if(isset($_COOKIE["recentVisitedSite"]))
	{
		$RecentSite = unserialize($_COOKIE["recentVisitedSite"]);
	}
	if(isset($_COOKIE["mostVisitedSite"]))
	{
		$MostVisitedSite = unserialize($_COOKIE["mostVisitedSite"]);
	}
}

include ("Database.php");
$conn = getDatabaseConnection();

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 
else
{
?>
	<div class="special">
	<div class="container">
	<h3 id="RecentVisited">Recently Visited</h3>
	<div class="specia-top">
	<ul class="grid_2">
<?php
	if(isset($RecentSite))
	{
?>		
<?php
		for($i = sizeof($RecentSite); $i > 0; $i--)
		{
			if($RecentSite[$i-1][0] != "")
			{
				$sql = "Select * FROM ". $RecentSite[$i-1][0]. " WHERE `Product number` = " . $RecentSite[$i-1][1];				
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();				
				$link = $row["Link"] . "?product=" .  $row["Product number"]. "&item=".$RecentSite[$i-1][0];
?>
				<div id="cloth">
				<a href=<?php echo $link; ?> target="_blank">
				<img src=<?php echo $row["Image"]; ?> height="200" width="150">
				</a>
				<div class="content_box">
				<div class="item_add"><span class="item_price"><h6><?php echo $row["Name"]; ?></h6></span></div>
				<div class="item_add"><span class="item_price"><h6>Price: $ <?php echo $row["Price"]; ?></h6></span></div>
				<div class="item_add"><span class="item_price"><h6>Discount: $ <?php echo $row["Discount"]; ?></h6></span></div>
				</div>
				</div>			
<?php				
			}
		}
?>		
<?php
	}
?>
	</ul>
	</div>
	</div>
	</div>
	<div class="special">
	<div class="container">
	<h3 id="MostVisited">Most Visited Product</h3>
	<div class="specia-top">
	<ul class="grid_2">
<?php
	if(isset($MostVisitedSite))
	{			
?>
		
<?php
		$item = 0;
		foreach($MostVisitedSite as $key=>$value)
		{
			$item += 1;
			if($item > 5)
				break;
			$data = explode("_", $key);
			$sql = "Select * FROM ". $data[0]. " WHERE `Product number` = " . $data[1];
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();			
			$link = $row["Link"] . "?product=" .  $row["Product number"]. "&item=".$data[0];
?>
			<div id="cloth">
			<a href=<?php echo $link; ?> target="_blank">
			<img src=<?php echo $row["Image"]; ?> height="200" width="150">
			</a>
			<div class="content_box">
			<div class="item_add"><span class="item_price"><h6><?php echo $row["Name"]; ?></h6></span></div>
			<div class="item_add"><span class="item_price"><h6>Price: $ <?php echo $row["Price"]; ?></h6></span></div>
			<div class="item_add"><span class="item_price"><h6>Discount: $ <?php echo $row["Discount"]; ?></h6></span></div>
			</div>
			</div>			
<?php
					
		}
?>
		
<?php
	}				
?>
	</ul>
	</div>
	</div>
	</div>
	<div class="special">
	<div class="container">
	<h3 id="MostPopular">Most Popular Product</h3>
	<div class="specia-top">
	<ul class="grid_2">
<?php
	$sql = "Select * FROM `Popular_Product` WHERE 1 ORDER BY 'Populariy' DESC";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0)
	{			
		$item = 0;
		while($row = $result->fetch_assoc())
		{
			$item += 1;
			if($item > 5)
				break;
			$data = explode("_", $row["Item"]);
			$sql = "Select * FROM ". $data[0]. " WHERE `Product number` = " . $data[1];
			$result1 = $conn->query($sql);
			$row = $result1->fetch_assoc();			
			$link = $row["Link"] . "?product=" .  $row["Product number"]. "&item=".trim($data[0]);
?>
			<div id="cloth">
			<a href=<?php echo $link; ?> target="_blank">
			<img src=<?php echo $row["Image"]; ?> height="200" width="150">
			</a>
			<div class="content_box">
			<div class="item_add"><span class="item_price"><h6><?php echo $row["Name"]; ?></h6></span></div>
			<div class="item_add"><span class="item_price"><h6>Price: $ <?php echo $row["Price"]; ?></h6></span></div>
			<div class="item_add"><span class="item_price"><h6>Discount: $ <?php echo $row["Discount"]; ?></h6></span></div>
			</div>
			</div>			
<?php
					
		}
	}
?>
	</ul>
	</div>
	</div>
	</div>
<?php	
}
?>
</body>
</html>