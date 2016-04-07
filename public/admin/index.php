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


<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li class="active"><span class="glyphicon glyphicon-home"></span></li>
	</ol>
</div>

<div class="well">
	<h2 class="page-header">Admin - Manage Content</h2>
</div>

<div class="container disp">
			
	<div class="panel-group" id="accordion">
		<div class="panel panel-info">
			<div class="panel-heading">
				<a href="#manageUsers" data-toggle="collapse" data-parent="#accordion">
					<h4 class="panel-title text-center"><span class="glyphicon glyphicon-hand-down"></span> Manage Users</h4>
				</a>
			</div>
			<div class="panel-collapse collapse" id="manageUsers">
				<div class="panel-body">
					<p><a href="manage_users.php"><button class="btn btn-primary btn-group-justified"><span class="glyphicon glyphicon-cog"></span> Manage Existing Users</button></a></p>
					<p><a href="create_user.php"><button class="btn btn-success btn-group-justified"><span class="glyphicon glyphicon-user"></span> Create New User</button></a></p>
				</div>
			</div>
		</div>	
	</div>	
	
	<a href="logfile.php"><button class="btn btn-info btn-group-justified"><span class="glyphicon glyphicon-file"></span> View Log File</button></a><br>
	
	<a href="<?= BASE_URL ?>admin/manage_images.php"><button class="btn btn-info btn-group-justified"><span class="glyphicon glyphicon-picture"></span> Manage Images</button></a><br>

	<div class="panel-group" id="accordion">
		<div class="panel panel-info">
			<div class="panel-heading">
				<a href="#uploadimg" data-toggle="collapse" data-parent="#accordion">
					<h4 class="panel-title text-center"><span class="glyphicon glyphicon-upload"></span> Upload Image</h4>
				</a>
			</div>
			<div class="panel-collapse collapse" id="uploadimg">
				<div class="panel-body">
					<!-- <div class="container"> -->
						<?php if(!empty($message)) : ?>
							<div class="alert alert-danger">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
								<?= nl2br(htmlentities($message)); ?>
							</div>
						<?php endif; ?>	

						<form method="post" enctype="multipart/form-data" action="uploads.php">

							<div class="form-group">
						    	<input id="myImages" name="images[]" class="file" type="file" multiple data-min-file-count="1" data-overwrite-initial="false"><br><br>
							</div>

						</form>
					<!-- </div> -->
				</div>
			</div>
		</div>	
	</div>

	<a href="<?= BASE_URL ?>admin/logout.php"><button class="btn btn-info btn-group-justified"><span class="glyphicon glyphicon-off"></span> Logout</button></a><br>

</div>

<p>&nbsp;</p>


<!-- default
max_input_time ==> -1(no limit)
upload_max_filesize ==> 2M  -->


<?php include( ROOT_PATH . "incs/admin-footer.php"); ?>

<script type="text/javascript">

    $("#myImages").fileinput({
    	uploadAsync: false,
        uploadUrl: '/photo_gallery/public/admin/uploads.php', // you must set a valid URL here else you will get an error
        //allowedFileExtensions : ['jpg', 'png','gif', 'jpeg'],
        maxImageWidth: 200,
    	resizeImage: true,
        allowedFileTypes: ['image'],
        slugCallback: function(filename) {
            return filename.replace('(', '-').replace(']', '-');
        }
	});


</script>