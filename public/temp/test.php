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

 ?>
 <?php include( ROOT_PATH . "incs/header.php"); ?>

<?php

	$db = new MySqlDatabase(); 
	$user = new User();

	if( $_POST )
	{
		//multiple inputs fields (a lot of them)
		// $vars = $db->createQuery($_POST); //@returns an array
		// Output Array 
		// (
		//     [0] => username, password, new
		//     [1] => :username, :password, :new
		// )

		// $query = "INSERT INTO temp ($vars[0]) VALUES ($vars[1])";
		
		// $db->insertQuery($query, $_POST);

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
			$end = ($logspec) ? "an Admin" : "user";
			log_action('sign up', "{$user->username} was added as " . $end );
			$_SESSION['message'] = ucfirst($user->username) . "'s info was added to the records successfully";
			redirect_to("create_user.php");
		}else{
			$_SESSION['message'] = "OOps! Something went wrong when we tried to create new user";	
		}
		
	}
	

 ?>

<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active">Create User</li>
	</ol>
</div>

<div class="well">
	<h2 class="page-header">Admin - Create User</h2>
</div>

<div class="container">
    <div class="container">
	    <div id="loginbox" style="margin-top:10px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
	        <div class="panel panel-info">
	            <div class="panel-heading">
	                <div class="panel-title">Create User</div>
	            </div>
	            <div style="padding-top:30px" class="panel-body">
	                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

	                <form id="loginform" class="form-horizontal" role="form" method="POST" action="create_user.php">
	                    <div style="margin-bottom: 25px" class="input-group">
	                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	                        <input id="username" type="text" class="form-control" name="username" placeholder="username" value="">
	                    </div>
	                    <div style="margin-bottom: 25px" class="input-group">
	                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
	                        <input id="password" type="password" class="form-control" name="password" placeholder="password" value="">
	                    </div>
	                    <div style="margin-bottom: 25px" class="input-group">
	                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	                        <input id="first_name" type="text" class="form-control" name="first_name" placeholder="First Name" value="">
	                    </div><div style="margin-bottom: 25px" class="input-group">
	                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	                        <input id="last_name" type="text" class="form-control" name="last_name" placeholder="Last Name" value="">
	                    </div>
	                    <div class="form-group container-fluid">
	                    	<label>Make user an Admin</label>
		                    <div class="input-group">
		                    	
		                        <div class="radio">
		                            <label>
		                                <input id="login-remember" type="radio" name="bool" value="0" > NO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                                <input id="login-remember" type="radio" name="bool" value="1" > YES
		                            </label>
		                        </div>
		                    </div>
	                    </div>
	                    
	                    <div style="margin-top:10px" class="form-group">
	                        <!-- Button -->
	                        <div class="col-sm-12 controls text-right">
	                            <input type="submit" class="btn btn-success" 
	                            		value="Create <?= isset($user->username) ? htmlentities($user->username) : ""; ?>">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="col-md-12 control">
	                            <?php if(isset($_SESSION['message']) && $_SESSION['message'] != "") : ?>
	                            	<div style="border-top: 1px solid #888; padding-top:15px; font-size:85% font-weight: bold;" class="alert alert-info">
	                                	<?= nl2br(htmlentities($_SESSION['message'])); ?>
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
<?php $_SESSION['message'] = ""; ?>