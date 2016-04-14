<?php 
	//controller - require() 2. model[db,classes] - require_once()  3. views[html] - include/require 
	require($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
	require_once( ROOT_PATH . "incs/_functions.php");

	require_once( ROOT_PATH . "incs/Session.php");
	require_once( ROOT_PATH . "incs/MySqlDatabase.php");

	require_once( ROOT_PATH . "incs/User.php");
	

	
	if(!$session->isLoggedIn()){
		redirect_to("login.php");
	}

	if (isset($_GET['id']) && trim($_GET['id']) === "") {
		redirect_to("manage_users.php");
	}

 ?>
 <?php include( ROOT_PATH . "incs/header.php"); ?>

<?php

	$db = new MySqlDatabase(); 

	$clean_id = intval($_GET['id']);
	$user = User::getById($clean_id);

	if(empty($user)){ redirect_to("manage_users.php"); }

	if( intval($session->user_id) !== intval($user->id) ) 
	{
		if($user->delete())
		{
			$message = $user->username .  " was removed";
			unset($user);
		}
	}else{
		$message = $user->username .  " is still logged in. \n Sign in with a different user and try again";
	}
	
 ?>

 <div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active">Delete User</li>
	</ol>
</div>

<div class="well">
	<h2 class="page-header">Admin - Remove User</h2>
</div>

<div class="container">
	<div class="alert alert-danger" style="margin-top: 30px;">
	    <div class="row" style="font-family: Georgia, serif; color: #f00">
	        <div class="col-md-1">
	            <i class="fa fa-frown-o fa-3x"></i>
	        </div>
	        <div class="col-md-11">
	            <h2 class="modal-title">
					<p><?= isset($message) ? nl2br(htmlentities($message)) : "Deleted"; ?></p>
				</h2>
	        </div>
	    </div>
	</div>

	<div class="well" style="margin-top: 30px;">
		<a href="manage_users.php" class="btn btn-primary btn-group-justified"><span class="glyphicon glyphicon-circle-arrow-left"></span> Go back</a>
	</div>
</div>





<?php include( ROOT_PATH . "incs/admin-footer.php"); ?>