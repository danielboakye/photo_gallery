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
	$profile_pic = new Images();

	$dp = $profile_pic->getProfileImg($user->id);

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
			$logspec = true;
		}else{
			$user->revokeAdmin();
			$logspec = false;
		}


		if( $user->save() )
		{
			if ( $_FILES['image']['error'] !== 4 )
			{
				//update the image if  any image was added
				if( isset($dp->uniqname) ){
					if(! $dp->delete()){
						$_SESSION['message'] = "File could not be removed. \n";
					}
					// else{
					// 	unlink();
					// }
				}
				
				//use Image class to upload image-profile picture

				$profile_pic->attach_file($_FILES['image']); 

				unset($dp->id);
			    $saved = $profile_pic->process($user->id, 1);
			   
			    $_SESSION['message'] = 'All files uploaded.';

			    if($saved === false)
			    { 
			        $_SESSION['message'] = 'Error while saving images. Contact the system administrator';
			        log_action('Upload', "{$user->username}, ID - {$user->id} had a failed upload."); 
			    }else{

			        log_action('Upload', "{$user->username}, ID - {$user->id} added profile image" );
			    }

				
				//============================================================================

				$end = ($logspec) ? "an Admin" : "user";
				log_action('sign up', "{$user->username} was added as " . $end );
				$_SESSION['message'] .= "\n" . ucfirst($user->username) . "'s info was added to the records successfully";

				foreach ($profile_pic->errors as $error ) {
					$_SESSION['message'] .= "\n" . $error;
				}


				$_SESSION['update_message'] = ucfirst($user->username) . "'s info was updated Successfully";
				redirect_to("edit_user.php?id=" . urlencode($uid) );
			}else{
				$_SESSION['update_message'] = "\n Successful. No changes were made to your profile picture";
			}
		}else{
			$_SESSION['update_message'] = "OOps! Something went wrong with the update";	
		}
		
	}
		
 ?>
<link rel="stylesheet" type="text/css" href="../css/jasny-bootstrap.min.css" media="all">

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

	                <form id="loginform" class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" 
	                		action="edit_user.php?id=<?= urlencode($uid); ?>">

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
		                        <input id="password" type="password" class="form-control" name="password" placeholder="password" required>
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
	                    <div>
	                    	<div class="row">

								<!-- query image with user id	 -->

								<div class="col-md-6" style="margin-bottom: 1em;">
									<img src="<?= isset($dp->location) ? BASE_URL . "admin/" . $dp->location : ""; ?>" data-src="holder.js/250x200/industrial" class="img-thumbnail img-responsive img-circle">
								</div>

								<div class="col-md-6">
									<div class="fileinput fileinput-new" data-provides="fileinput">
									  <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
									    <img data-src="holder.js/250x200/industrial" alt="..." class="img-responsive">
									  </div>
									  <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
									  <div>
									    <span class="btn btn-primary btn-file">
									    	<span class="fileinput-new">Change image</span>
									    	<span class="fileinput-exists">Change</span>
									    	<input type="file" name="image">
									    </span>
									    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
									  </div>
									</div>
								</div>
							</div>
	                    </div>
	                    <div style="margin-top: 15px; border-top: 1px solid #31708f; padding-top:15px;" class="form-group">
	                        <!-- Button -->
	                        <div class="col-sm-12 controls text-right">
	                            <input type="submit" class="btn btn-success" 
	                            		value="Update <?= isset($user->username) ? htmlentities($user->username) : ""; ?>">
	                        </div>
	                    </div>
	                    <div class="form-group" >
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


<script type="text/javascript" src="<?= BASE_URL ?>js/jquery-2.1.4.min.js"></script>		
<script type="text/javascript" src="<?= BASE_URL ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>js/jasny-bootstrap.min.js"></script>
<script src="<?= BASE_URL ?>js/jquery.easing.min.js" type="text/javascript"></script> 

<script type="text/javascript">$('.fileinput').fileinput();</script>

<script type="text/javascript" src="<?= BASE_URL ?>js/plugins/holder.min.js"></script>

</body>
</html>

<?php if(isset($db)) { $db->close_connection(); } ?>  
<?php $_SESSION['message'] = ""; ?>
<?php $_SESSION['update_message'] = ""; ?>
