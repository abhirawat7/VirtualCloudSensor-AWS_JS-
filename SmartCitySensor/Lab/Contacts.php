<!doctype html>
<html>
<head>
</head>
<boby>
<?php include("Navigation.php");?>


<section id="intro">
<h4>Contact details:</h4>
<pre>
<?php
$Contacts = fopen("Contact_list.txt", "r") or die("Unable to open file!");
echo fread($Contacts,filesize("Contact_list.txt"));
fclose($Contacts);
?>
</pre>
</section>


</boby>
</html>
