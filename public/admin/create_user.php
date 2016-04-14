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

 ?>
 <?php include( ROOT_PATH . "incs/header.php"); ?>

<?php

	$db = new MySqlDatabase(); 
	$user = new User();
	$myImages = new Images();

	if( $_POST )
	{
		// $error = $_FILES['image']['error'];

		// if(isset($errors) && $errors > 0){
		// 	$_SESSION['message'] = $myImages->upload_errors[$error];
		// 	redirect_to("create_user.php");
		// }

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

		// check if username already exits in the database the send error message and redirect_to(create user )

		if( $saved_user = $user->save() )
		{

			//=========================================================================

			$username = User::getById($saved_user)->username;

			//use Image class to upload image-profile picture
			$myImages->attach_file($_FILES['image']);

		    $saved = $myImages->process($saved_user, 1);
		   
		    $_SESSION['message'] = 'All files uploaded.';

		    if($saved === false)
		    { 
		        $_SESSION['message'] = 'Error while saving images. Contact the system administrator';
		        log_action('Upload', "{$username}, ID - {$saved_user} had a failed upload."); 
		    }else{

		        log_action('Upload', "{$username}, ID - {$saved_user} added profile image" );
		    }

			
			//============================================================================

			$end = ($logspec) ? "an Admin" : "user";
			log_action('sign up', "{$user->username} was added as " . $end );
			$_SESSION['message'] .= "\n" . ucfirst($user->username) . "'s info was added to the records successfully";

			foreach ($myImages->errors as $error ) {
				$_SESSION['message'] .= "\n" . $error;
			}

			redirect_to("create_user.php");

		}else{
			$_SESSION['message'] = "OOps! Something went wrong when we tried to create new user";	
		}



		
	}
	

 ?>

<link rel="stylesheet" type="text/css" href="../css/multistep.css">
<link rel="stylesheet" type="text/css" href="../css/jasny-bootstrap.min.css" media="all">

<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active">Create User</li>
	</ol>
</div>

<div class="well" style="margin-bottom: 0px;">
	<h2 class="page-header">Admin - Create User</h2>
</div>

<!-- <div class="container"> -->
    <!-- <div class="container"> -->
	    <div id="" class="mainbox col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
	       
            <!-- <div style="" class="panel-body"> -->
                <!-- <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div> -->

                <form id="msform" class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="create_user.php">
                	<!-- progressbar -->
					<ul id="progressbar">
						<li class="active">User Details</li>
						<li>Add profile picture</li>
					</ul>

					<!-- fieldsets -->
					<fieldset>
					<h2 class="fs-title">Create the account</h2>
					<h3 class="fs-subtitle">This is step 1</h3>
                    <div style="margin-bottom: 15px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="username" type="text" class="form-control" name="username" placeholder="username" value="" required>
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="password" type="password" class="form-control" name="password" placeholder="password" value="" required>
                    </div>
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="first_name" type="text" class="form-control" name="first_name" placeholder="First Name" value="" required>
                    </div><div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="last_name" type="text" class="form-control" name="last_name" placeholder="Last Name" value="" required>
                    </div>
                    <div class="form-group container-fluid">
                    	<label for="bool" style="float: left;">Make user an Admin</label>
	                    <div class="input-group" style="clear: both;">
	                    	
	                        <div class="radio">
	                            <label>
	                                <input id="login-remember" type="radio" name="bool" value="0" checked> NO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                                <input id="login-remember" type="radio" name="bool" value="1" > YES
	                            </label>
	                        </div>
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

					<input type="button" name="next" class="next action-button" value="Next" />
                    </fieldset>

                    <fieldset>

                    	<div class="fileinput fileinput-new" data-provides="fileinput">
						  <div class="fileinput-new thumbnail" style="width: 250px; height: 200px;">
						    <img data-src="holder.js/250x200/industrial" alt="...">
						  </div>
						  <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
						  <div>
						    <span class="btn btn-info btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="image"></span>
						    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
						  </div>
						</div><br><br>
															   
                    	<input type="button" name="previous" class="previous action-button" value="Previous" />
                    	<input type="submit" class="submit action-button" value="Create User">
                    </fieldset>
                </form>
            <!-- </div> -->
	        
	    </div>
	<!-- </div> -->
<!-- </div> -->


<script type="text/javascript" src="<?= BASE_URL ?>js/jquery-2.1.4.min.js"></script>		
<script type="text/javascript" src="<?= BASE_URL ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= BASE_URL ?>js/jasny-bootstrap.min.js"></script>
<script src="<?= BASE_URL ?>js/jquery.easing.min.js" type="text/javascript"></script> 

<!-- Jansy bootstrap fileinput JS -->
<script type="text/javascript">$('.fileinput').fileinput();</script>

<script type="text/javascript" src="<?= BASE_URL ?>js/plugins/holder.min.js"></script>
<script>
$(function() {

//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(".next").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	next_fs = $(this).parent().next();
	
	//activate next step on progressbar using the index of next_fs
	$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
	
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'transform': 'scale('+scale+')'});
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".previous").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	
	//de-activate current step on progressbar
	$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".submit").click(function(){
	return true;
})

});
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


</body>
</html>

<?php if(isset($db)) { $db->close_connection(); } ?>  
<?php $_SESSION['message'] = ""; ?>