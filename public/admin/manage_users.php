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

	if (isset($_GET['s']) && trim($_GET['s']) === "") {
		redirect_to("manage_users.php");
	}

 ?>
 <?php

 	$db = new MySqlDatabase();
 	$all_users = User::findAll(); 

 	if(isset($_GET['s']) && $_GET['s'] !== "")
 	{
 		$s = htmlspecialchars(trim($_GET['s']));
 		//trying to search with multiple keywords
 		// $key_array = explode(" ", $s);
 		// foreach ($key_array as $s) {
 		// 	$all_users[] = User::getByKeyword($s);
 		// }
 		// echo "<pre>";print_r($all_users);die();
 		$all_users = User::getByKeyword($s);


 	}
	
 ?>
 <?php include( ROOT_PATH . "incs/header.php"); ?>

 <div id="loginModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-family: Georgia, serif;">Remove User </h4>
            </div>
            <div class="modal-body">
                <div class="panel-heading list-group list-group-item-danger">
                    <h3 class="list-group-item" style="font-family: comic;">Are you sure you want to delete this user?</h3>
                </div>           
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Dismiss</button>
                <a id="acceptDel"><button type="button" class="btn btn-success">Accept</button></a>
            </div>
        </div>
    </div>
</div>

<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active">Manage Users</li>
	</ol>
</div>

<div class="well">
	<h2 class="page-header">Admin - Manage Users</h2>
</div>

<!-- search bar -->
<div class="container" style="margin-top: 50px;">
	<div class="row">
		<div class="container">
			<form method="GET" action="">
				<div class="input-group">
					<input type="text" class="form-control" id="s-input" placeholder="Search for user in records" name="s">
					<span class="input-group-btn">
						<button class="btn btn-primary" style="height: 50px;" type="submit">Search</button>
					</span>
				</div><!-- /input-group -->
			</form>
		</div>
	</div>
</div>



<style type="text/css" media="all">
	.panel-title, .list-group-item{ font-family: cursive; font-weight: bold; } 
	.panel-body{ font-family: monospace; font-weight: bolder; }
</style>

 <div class="container" style="margin-top: 40px;">
 	<?php if( isset($all_users) && !empty($all_users) ) : ?>

		<h2 class="page-header" style="margin-bottom: 10px; font-family: comic; font-weight: bold;">
			<?= isset($s) ? "User with key: " . htmlentities($s) : "All Users"; ?>
		</h2>		

		<div class="panel-group" id="accordion">
			<?php $counter = 0; ?>
			<?php foreach ($all_users as $user) : ?>
				<?php $counter++; ?>
				<div class="panel panel-info">
					<div class="panel-heading disp">
						<a href="<?= "#content-" . $user->id; ?>" data-toggle="collapse" data-parent="#accordion">
							<h3 class="panel-title"><?= ($user->is_admin == 1) ? "<span class='glyphicon glyphicon-lock'></span> " : 
								"<span class='glyphicon glyphicon-eye-open'></span> "; ?>
								<?= strtoupper(htmlentities($user->username)); ?><span class="badge" style="float: right;"><?= $counter; ?></span></h3>
						</a>
					</div>
					<div class="panel-collapse collapse <?= ($user->id == 1) ? 'in' : '' ?>" id="<?= "content-" . $user->id; ?>">
						<div class="panel-body disp">
							<div class="row">

								<!-- query image with that image	 -->

								<div class="col-md-3">
									<img src="" data-src="holder.js/220x150/industrial" class="img-responsive">
									<!-- src = < ?= urlencode( image location ) ?> -->
								</div>
								<div class="col-md-9" style="line-height: 100%; padding-top: 3%;">
									<p>
										<a href="edit_user.php?id=<?= $user->id; ?>"><button class="btn btn-success btn-group-justified">Edit 
										<?= strtoupper(htmlentities($user->username)); ?>&rsquo;s details</button></a>
									</p>
									<p>
										<a data-toggle="modal" data-target="#loginModal" class="delUser"><button class="btn btn-danger btn-group-justified" uid="<?= urlencode($user->id); ?>">Remove <?= strtoupper(htmlentities($user->username)); ?> from records</button></a>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>	
		</div>

 	<?php else : ?>
 		<div class="alert alert-danger">
 			<div class="row" style="font-family: Georgia, serif; color: #f00">
 				<div class="col-md-1">
 					<i class="fa fa-frown-o fa-3x"></i> 	
 				</div>
 				<div class="col-md-11">
			 		<h2 class="modal-title">
						<p>Sorry! There is no data in the records.</p>
						<p>Contact the system database Administrator</p>  
					</h2> 
 				</div>
 			</div>
		</div>
		<div class="well">
			<a href="manage_users.php" class="btn btn-primary btn-group-justified">Refresh page</a>
		</div>
 	<?php endif; ?>	
</div>	

<?php include( ROOT_PATH . "incs/admin-footer.php"); ?>

<script>
	$(document).ready(function(){
		var uid;
		$('.delUser').click(function() {
			uid = $(this).find('button').attr('uid');

		});

		$('#acceptDel').click(function () {
			window.location.href = 'delete_user.php?id='+uid;
		});
	});
</script>
<script type="text/javascript" src="<?= BASE_URL ?>js/plugins/holder.min.js"></script>