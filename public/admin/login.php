<?php 
	//controller
	require($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
	//load basic function so everything else can use them
	require_once( ROOT_PATH . "incs/_functions.php");	
	//load core objects
	require_once( ROOT_PATH . "incs/Session.php");	
	require_once( ROOT_PATH . "incs/MySqlDatabase.php");
	//load db related objects
	require_once( ROOT_PATH . "incs/User.php");
	

	if($session->isLoggedIn()){
		redirect_to("./");  /*@function send to home = current directory index.php*/
	}

	if($_POST)
	{
		$username = trim(htmlspecialchars($_POST['username']));
		$password = $_POST['password'];

		$db = new MySqlDatabase();

		$found_user = User::authenticateAdmin($username, $password);

		if($found_user){
			$session->login($found_user);
			log_action('Login', "{$found_user->username} logged in");
			redirect_to("./");
		}else{
			$message = "Username or Password is Incorrect!<br> Please try again.";
		}
 	}

 ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Ark Inc! Photo Gallery</title>
	<link rel="stylesheet" href="../css/login-animate.css">
	<link rel="stylesheet" href="../css/login.css">
	<link rel="stylesheet" type="text/css" href="../css/font-awesome.min.css">
	<script src="../js/jquery-2.1.4.min.js"></script>
</head>

<body>
	<div class="container">
		<div class="top">
			<h1 id="title" class="hidden"><span id="logo">Ark <span>Inc!</span></span></h1>
		</div>
		<div class="login-box animated fadeInUp">
			<div class="box-header">
				<h2 style="font-family: comic; font-weight: bolder;">Admin Login</h2>
				<!-- font droid serif -->
			</div>
			<form method="post" action="login.php">
				<label for="username">Username</label>
				<br/>
				<input type="text" id="username" name="username">
				<br/>
				<label for="password">Password</label>
				<br/>
				<input type="password" id="password" name="password">
				<br/>
				<input type="hidden" id="new" name="new" value=""> 
				<button type="submit">Sign In</button>
			</form>
			<br/>
			<span style="color: red; font-family: cursive;"><?= isset($message)? $message : "" ?></span>
			<a href="#"><p class="small">Forgot your password?</p></a>
		</div>
	</div>
</body>

<script>
	$(document).ready(function () {
    	$('#logo').addClass('animated fadeInDown');
    	$("input:text:visible:first").focus();
	});
	$('#username').focus(function() {
		$('label[for="username"]').addClass('selected');
	});
	$('#username').blur(function() {
		$('label[for="username"]').removeClass('selected');
	});
	$('#password').focus(function() {
		$('label[for="password"]').addClass('selected');
	});
	$('#password').blur(function() {
		$('label[for="password"]').removeClass('selected');
	});
</script>

</html>
<?php if(isset($db)) { $db->close_connection(); } ?>