<!doctype html>
<html>
<head>
<style>
#Books, #Clothes, #Music, #Accessories
{
	text-align:center;
	font-size:200%;
</style>
</head>
<body>
<?php include("Navigation.php");?>

<div class="container">
<p id="Books">Books</p>
<iframe src="Books_list.php" height="840" width="1200" align="center"></iframe></br></br></br></br>
</div>

<div class="container">
<p id="Clothes">Clothes</p>
<iframe src="Clothes_list.php" height="1000" width="1200" align="middle"></iframe></br></br>
</div>

<div class="container">
<p id="Music">Music</p>
<iframe src="Music_list.php" height="1000" width="1200" align="middle"></iframe></br></br>
</div>

<div class="container">
<p id="Accessories">Accessories</p>
<iframe src="Accessories_list.php" height="1000" width="1200" align="middle"></iframe>
</div>

</body>
</html>