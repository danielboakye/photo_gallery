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
	$display = Images::findAll();

 ?>
<?php include( ROOT_PATH . "incs/header.php"); ?>

<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active">Photos</li>
	</ol>
</div>

<div class="well" style="margin-bottom: 0">
	<h2 class="page-header">Admin - Manage Images</h2>
</div>

<!-- display message -->


<style type="text/css">

	.white, .white a {
	  color: #fff;
	  -ms-zoom: 3;
	  zoom: 3;
	}
	.show{
		padding: 0;
		margin: 0;
		background-color: #333;
	}

	.disp img{ width: 100%; height: 350px;}
</style>

<?php if( isset($display) && !empty($display) ) : ?>
	<div class="container disp">
		
		<?php foreach ($display as $img) : ?>
			<div class="col-sm-3 show">
				<a class="img-responsive" href="<?= $img->location; ?>" data-lightbox="example-set" data-title="<?= $img->caption; ?>" 
					title="<?= $img->caption; ?>">
					<img class="img-responsive img-thumbnail" src="<?= $img->location;  ?>" alt="<?= $img->caption; ?>" >
				</a>
				
				<a href="delete_image.php?id=<?= $img->uniqname; ?>"><span class="glyphicon glyphicon-remove-circle white"></span></a>
				<a href="rename_image.php?id=<?= $img->uniqname; ?>"><span class="glyphicon glyphicon-edit white"></span></a>
				<a href="display_image.php?id=<?= $img->uniqname; ?>"><span class="glyphicon glyphicon-comment white"></span></a>
			
			</div>
		<?php endforeach; ?>	
	   
	</div>
<?php else : ?>
  <h3 class="alert alert-danger" style="height: 370px; margin: 0;"> Error! NO images to display</h3>
<?php endif; ?> 
	

 <?php include( ROOT_PATH . "incs/admin-footer.php"); ?>