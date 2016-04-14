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
	
	$pic = Images::getByUniq( htmlspecialchars($_GET['id']) );

	if( $pic && $pic->destroy() )
	{
		$session->message("Image was removed");
		log_action('Delete', "ID - {$session->user_id} removed image {$pic->caption}" );
	}
	else
	{
		$session->message("Image could not be removed");
		log_action('Delete', "ID - {$session->user_id} tried to remove image {$pic->caption}. FAiled Attempt!" );
	}

	redirect_to("manage_images.php");

 ?>


 <?php if(isset($db)) { $db->close_connection(); } ?>