<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
require_once( ROOT_PATH . "incs/_functions.php");

require_once( ROOT_PATH . "incs/Session.php");
require_once( ROOT_PATH . "incs/MySqlDatabase.php");

require_once( ROOT_PATH . "incs/User.php");
require_once( ROOT_PATH . "incs/Images.php");

if(!$session->isLoggedIn()){
    redirect_to("login.php");
}

?>

<?php 
	
	$db = new MySqlDatabase();

	if ( $_GET )
	{
		$pic = Images::getByUniq( htmlspecialchars($_GET['id']) );
		$_SESSION['uniqid'] = $pic->uniqname;
	}
	elseif ( $_POST )
	{
		$pic = Images::getByUniq( $_SESSION['uniqid'] );

		if($pic)
		{
			$pic->caption = htmlspecialchars($_POST['caption']);
		}

		if( $pic->process() )
		{
			$session->message("An Image was renamed");
			log_action('Rename', "ID - {$session->user_id} renamed image {$pic->caption}" );
			redirect_to("rename_image.php");
		}
		else
		{
			$session->message("Rename failed");
			log_action('Rename', "ID - {$session->user_id} tried to rename image {$pic->caption}. FAiled Attempt!" );
		}
	}
	else
	{
		redirect_to("manage_images.php");
	}	

?>
 <?php include( ROOT_PATH . "incs/header.php"); ?>


<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active"><?= isset($pic->caption) ? htmlentities($pic->caption) : "Image" ?></li>
	</ol>
</div>

<div class="well">
	<h2 class="page-header">Admin - Edit Image caption</h2>
</div>

<?php if ( $pic && !empty($pic) ) : ?>
	<div class="container">
		<form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
			<div class="form-group" class="col-md-12">
				<input type="text" name="caption" class="form-control" value="<?= isset($pic->caption) ? htmlentities($pic->caption) : "" ?>">
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success btn-group-justified" value="Rename">
			</div>
		</form>
	</div>

	<div class="container">
		<img class="img-responsive img-thumbnail" src="<?= $pic->location; ?>" alt="<?= $pic->caption; ?>">
	</div>
<?php else : ?>
	<div class="alert alert-danger">Could not load resource</div>
	<a href="manage_images.php" class="btn btn-warning btn-group-justified">Go back</a>
<?php endif; ?>	

<?php include( ROOT_PATH . "incs/admin-footer.php"); ?>