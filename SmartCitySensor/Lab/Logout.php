<?php
session_start();
?>
<!doctype html?
<html>
<head>
</head>
<body>
<?php
$_SESSION["CurrentUser"] = "";
session_write_close();
header("Location: index.php");	
include("Navigation.php");
?>
</body>
</html>