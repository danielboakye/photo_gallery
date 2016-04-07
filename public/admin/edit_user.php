<?php 
	//controller - require() 2. model[db,classes] - require_once()  3. views[html] - include/require 
	require($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
	require_once( ROOT_PATH . "incs/_functions.php");

	require_once( ROOT_PATH . "incs/Session.php");
	require_once( ROOT_PATH . "incs/MySqlDatabase.php");

	require_once( ROOT_PATH . "incs/User.php");
	require_once( ROOT_PATH . "incs/Images.php");
	

	
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
	$uid = intval($_GET['id']);
	$user = User::getById($uid);

	if( empty($user) ){
		redirect_to("manage_users.php");
	}

	if( $_POST )
	{
		$user->username = trim(htmlspecialchars($_POST['username']));
		$user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$user->first_name = trim(htmlspecialchars($_POST['first_name']));
		$user->last_name = trim(htmlspecialchars($_POST['last_name']));
		$make_admin = intval($_POST['bool']);

		if($make_admin === 1){
			$user->upgradeToAdmin();
		}else{
			$user->revokeAdmin();
		}
		if( $user->save() )
		{
			$_SESSION['update_message'] = ucfirst($user->username) . "'s info was updated Successfully";
			redirect_to("edit_user.php?id=" . urlencode($uid) );
		}else{
			$_SESSION['update_message'] = "OOps! Something went wrong with the update";	
		}
		
	}
		
 ?>

 <div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active">Edit <?= isset($user->username) ? htmlentities($user->username) : ""; ?></li>
	</ol>
</div>

<div class="well">
	<h2 class="page-header">Admin - Edit User Info</h2>
</div>

<div class="container">
    <div class="container">
	    <div id="loginbox" style="margin-top:10px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
	        <div class="panel panel-info">
	            <div class="panel-heading">
	                <div class="panel-title">Edit <?= isset($user->username) ? htmlentities($user->username) : "User" ?> </div>
	            </div>
	            <div style="padding-top:30px" class="panel-body">
	                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

	                <form id="loginform" class="form-horizontal" role="form" method="POST" action="edit_user.php?id=<?= urlencode($uid); ?>">

	                <!-- add image here -->

	                	<div class="form-group container-fluid">
	                		<label for="username">Username</label>
		                    <div style="margin-bottom: 5px" class="input-group">
		                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		                        <input id="username" type="text" class="form-control" name="username" placeholder="username" 
		                        	value="<?= isset($user->username)? htmlentities($user->username) : "" ?>">
		                    </div>
	                	</div>
	                	<div class="form-group container-fluid">
	                		<label for="password">Password</label>
		                    <div style="margin-bottom: 5px" class="input-group">
		                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
		                        <input id="password" type="password" class="form-control" name="password" placeholder="password" value="">
		                    </div>
	                	</div>
	                	<div class="form-group container-fluid">
	                		<label for="first_name">First Name</label>
		                    <div style="margin-bottom: 5px" class="input-group">
		                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		                        <input id="first_name" type="text" class="form-control" name="first_name" placeholder="First Name" 
		                        	value="<?= isset($user->first_name)? htmlentities($user->first_name) : "" ?>">
		                    </div>
	                	</div>
	                    
	                    <div class="form-group container-fluid">
							<label for="last_name">Last Name</label>
		                    <div style="margin-bottom: 5px" class="input-group">
		                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		                        <input id="last_name" type="text" class="form-control" name="last_name" placeholder="Last Name" 
		                        	value="<?= isset($user->last_name)? htmlentities($user->last_name) : "" ?>">
		                    </div>	                    	
	                    </div>
	                    <div class="form-group container-fluid">
	                    	<label for="bool">Make user an Admin</label>
		                    <div class="input-group">
		                        <div class="radio">
		                            <label>
		                                <input id="bool" type="radio" name="bool" value="0" <?= (isset($user->is_admin) && $user->is_admin == 0)? "checked" : "" ?>> NO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                                <input id="bool" type="radio" name="bool" value="1" <?= (isset($user->is_admin) && $user->is_admin == 1)? "checked" : "" ?>> YES

		                            </label>
		                        </div>
		                    </div>
	                    </div>
	                    <div style="margin-top:10px" class="form-group">
	                        <!-- Button -->
	                        <div class="col-sm-12 controls text-right">
	                            <input type="submit" class="btn btn-success" 
	                            		value="Update <?= isset($user->username) ? htmlentities($user->username) : ""; ?>">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="col-md-12 control">
	                            <?php if( isset($_SESSION['update_message']) && $_SESSION['update_message'] != "" ) : ?>	
	                            	<div style="border-top: 1px solid #888; padding-top:15px; font-size:85% font-weight: bold;" class="alert alert-info">
	                                	<?= nl2br( htmlentities( $_SESSION['update_message'] ) ); ?>
	                            	</div>
	                            <?php endif; ?>		
	                        </div>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
</div>

<?php include( ROOT_PATH . "incs/admin-footer.php"); ?>
<?php $_SESSION['update_message'] = ""; ?>
