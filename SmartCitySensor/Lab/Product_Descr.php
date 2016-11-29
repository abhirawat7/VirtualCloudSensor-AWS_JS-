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
	$conn = getDatabaseConnection();

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	else
	{    
		
		$sql = "Select * FROM ". $_GET["item"]. " WHERE `Product number` =" . $_GET["product"];		
		
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{        
			$row = $result->fetch_assoc();
			
			//Increase the popularity rank
			
			$popularity = $row["Popularity"]+1;
			$sql = "UPDATE ".  $_GET["item"].  " SET `Popularity`= ". $popularity ." WHERE `Product number` =" . $_GET["product"];
			$conn->query($sql);
			
			$sql = "Select * FROM `Popular_Product` WHERE `Item` LIKE '%" . $_GET["item"]."_".$_GET["product"]. "%'";
			$result1 = $conn->query($sql);
			if ($result1->num_rows > 0)
			{
				$row1 = $result1->fetch_assoc();				
				
				$popularity = $row["Popularity"]+1;
				$sql = "UPDATE `Popular_Product` SET `Popularity`= ". $popularity ." WHERE `Item` LIKE '%" . $_GET["item"]."_".$_GET["product"]. "%'";
				$conn->query($sql);
			}
			else
			{
					$sql = "INSERT INTO `Popular_Product` (`Item`, `Popularity`) Values(' ". $_GET["item"]."_".$_GET["product"]. "', 1)" ;
					$conn->query($sql);
			}
			
			//Popularity rank increase ends			
		
			//General Browser cookies data
			if(isset($_COOKIE["recentVisitedSite"]))
			{
				$recentSite = unserialize($_COOKIE["recentVisitedSite"]);				
				$check = false;
				for($i = 0; $i < 5; $i++)
				{
					if(trim($recentSite[$i][0]) == trim($_GET["item"]) && trim($recentSite[$i][1]) == trim($_GET["product"]))
					{
						$check = true;
						break;
					}
				}
				
				if($check == false)
				{
					array_shift($recentSite);
					$recentSite[4][0] = $_GET["item"];
					$recentSite[4][1] = $_GET["product"];
				}
				
				
			}
			else
			{
					$recentSite[0][0] = "";
					$recentSite[0][1] = "";
					$recentSite[1][0] = "";
					$recentSite[1][1] = "";
					$recentSite[2][0] = "";
					$recentSite[2][1] = "";
					$recentSite[3][0] = "";
					$recentSite[3][1] = "";		
					$recentSite[4][0] = $_GET["item"];
					$recentSite[4][1] = $_GET["product"];
			}
			
			setcookie("recentVisitedSite", serialize($recentSite), time()+ 360*24*60*60);
			
			if(isset($_COOKIE["mostVisitedSite"]))
			{
					$mostVisitedSite = unserialize($_COOKIE["mostVisitedSite"]);
					if(array_key_exists($_GET["item"]."_".$_GET["product"], $mostVisitedSite))
					{
						$mostVisitedSite[$_GET["item"]."_".$_GET["product"]] += 1;
					}
					else
					{
						$mostVisitedSite[$_GET["item"]."_".$_GET["product"]] = 1;
					}
			}
			else
			{
				$mostVisitedSite[$_GET["item"]."_".$_GET["product"]] = 1;
			}
			arsort($mostVisitedSite);
			setcookie("mostVisitedSite", serialize($mostVisitedSite), time()+ 360*24*60*60);				
			
			//User specific
			if (isset($_SESSION["CurrentUser"]) && $_SESSION["CurrentUser"] != "")
			{	
				if(isset($_COOKIE[$_SESSION["CurrentUser"]."recentVisitedSite"]))
				{
					$UserRecentSite = unserialize($_COOKIE[$_SESSION["CurrentUser"]."recentVisitedSite"]);
					array_shift($UserRecentSite);
					
				}
				else
				{
						$UserRecentSite[0][0] = 0;
						$UserRecentSite[0][1] = 0;
						$UserRecentSite[1][0] = 0;
						$UserRecentSite[1][1] = 0;
						$UserRecentSite[2][0] = 0;
						$UserRecentSite[2][1] = 0;
						$UserRecentSite[3][0] = 0;
						$UserRecentSite[3][1] = 0;						
				}
				$UserRecentSite[4][0] = $_GET["item"];
				$UserRecentSite[4][1] = $_GET["product"];
				setcookie($_SESSION["CurrentUser"]."recentVisitedSite", serialize($UserRecentSite), time()+ 360*24*60*60);
				if(isset($_COOKIE[$_SESSION["CurrentUser"]."mostVisitedSite"]))
				{
						$userMostVisitedSite = unserialize($_COOKIE[$_SESSION["CurrentUser"]."mostVisitedSite"]);
						if(array_key_exists($_GET["item"]."_".$_GET["product"], $userMostVisitedSite))
						{
							$userMostVisitedSite[$_GET["item"]."_".$_GET["product"]] += 1;
						}
						else
						{
							$userMostVisitedSite[$_GET["item"]."_".$_GET["product"]] = 1;
						}
				}
				else
				{
					$userMostVisitedSite[$_GET["item"]."_".$_GET["product"]] = 1;
				}
				arsort($userMostVisitedSite);
				setcookie($_SESSION["CurrentUser"]."mostVisitedSite", serialize($userMostVisitedSite), time()+ 360*24*60*60);
			}
			
?>			
			<div class="container">
			<div class="women_main">
				<div class="row single">
					<div class="col-md-9 det">
						<div class="single_left">
							
							
							
						<div class="grid images_3_of_2">
							<img class="etalage_source_image" src=<?php echo $row["Image"]; ?>  height="300" width="200" class="img-responsive" title="" />
							
							 <div class="clearfix"></div>		
				  </div>
				  
				  
							<div class="desc1 span_3_of_2">
								<h3><?php echo $row["Name"]; ?></h3>								
								<br>
								<span class="code">Product Code: <?php  echo $_GET["item"]. ":". $row['Product number'];?></span>								
								<div class="price">
									<span class="text">Price:</span>
									<span class="price-new">$<?php echo $row["Price"]; ?></span> 
									<span class="price-tax">Discount: <?php echo $row["Discount"]; ?></span><br>
								</div>				
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="single-bottom1">
						<h6>Details</h6>
						<p class="prod-desc"><?php echo $row["Name"]; ?></p>
					</div>				
				</div>	
				
				<div class="clearfix"></div>		
			</div>
			</div>
<?php        
		}
	}
?>
</body>
</html>