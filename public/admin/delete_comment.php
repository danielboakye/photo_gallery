<?php 
	//controller - require() 2. model[db,classes] - require_once()  3. views[html] - include/require 
	require($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
	require_once( ROOT_PATH . "incs/_functions.php");

	require_once( ROOT_PATH . "incs/Session.php");
	require_once( ROOT_PATH . "incs/MySqlDatabase.php");

	require_once( ROOT_PATH . "incs/User.php");
	require_once( ROOT_PATH . "incs/Images.php");
	require_once( ROOT_PATH . "incs/Comment.php");

	
	if(!$session->isLoggedIn()){
		redirect_to("login.php");
	}

	if (isset($_GET['id']) && trim($_GET['id']) === "" || empty($_GET['id'])) {
		redirect_to("manage_images.php");
	}

 ?>

<?php

	$db = new MySqlDatabase(); 

	$clean_id = intval($_GET['id']);
	$comment = Comment::getById($clean_id);

	if(empty($comment)){ redirect_to("manage_images.php"); }

	if($comment->delete())
	{
		$message = $comment->author .  "'s comment was removed";
		$_SESSION['message'] = $message;
		log_action('Delete', $message);
		unset($user);
	}

	redirect_to("display_image.php?id=" . urldecode($comment->image_uniqid));
	

 ?>
