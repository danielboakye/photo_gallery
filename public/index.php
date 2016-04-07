<?php 
	//controller
	require($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
	require( ROOT_PATH . "incs/_functions.php");
	
	require_once( ROOT_PATH . "incs/MySqlDatabase.php");
	require_once( ROOT_PATH . "incs/User.php");
	

	$database = new MySqlDatabase();
	// use $db as a reference to object $database
	$db =& $database;
	
	$user = new User();
	echo "<pre>";
	$users = User::findAll();
	foreach ($users as $user) 
	{
		echo "User: " . $user->username . "<br>";
		echo "Full Name: " . $user->fullName() . "<hr>";
	}

	// display all images if strtolower(pathinfo($image->location, PATHINFO_EXTENSION))  !== blob && strtolower(caption) !== blob



 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Photo Gallery</title>
 </head>
 <body>
 <!-- view -->
 </body>
 </html>

