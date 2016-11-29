<?php
	function getDatabaseConnection()
	{
		//return new mysqli("localhost", "gargoosc_myDB","Pry@00500","gargoosc_onestopshop");
		return new mysqli("localhost", "root","","onestopshop");
	}
?>