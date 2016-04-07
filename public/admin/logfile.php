<?php 
	//controller - require() 2. model[db,classes] - require_once()  3. views[html] - include/require 
	require($_SERVER['DOCUMENT_ROOT'] . "/photo_gallery/incs/config.php");
	require_once( ROOT_PATH . "incs/_functions.php");
	require_once( ROOT_PATH . "incs/Session.php");
	
	if(!$session->isLoggedIn()){
		redirect_to("login.php");
	}

	if( isset($_GET['clear']) && $_GET['clear'] == 'true' )
	{
		file_put_contents($log_file, "");
		log_action("Logs Cleared", "by User ID - {$session->user_id}");
		redirect_to("logfile.php");
	}

 ?>
 <?php include( ROOT_PATH . "incs/header.php"); ?>
 <style type="text/css">
	.panel-title{
		line-height: 22px; font-size: 20px; font-family: monospace;
	}
	.log-entries li{
		/*height: 35px;*/
		border-radius: 5px;
		line-height: 35px;
		padding-left: 2%;
		padding-right: 2%;
		list-style: none;
		font-family:  Georgia, serif;
		font-weight: bold;
		font-size: 15.5px;
		width: 90%;
	}
	.log-entries li:nth-child(odd){
		background-color: #e1e1e1;
	}
</style>

<div class="section no-padding" style="margin-top: 50px;">
	<ol class="breadcrumb">
		<li><a href="./"><span class="glyphicon glyphicon-home"></span></a></li>
		<li class="active">Log File</li>
	</ol>
</div>

<div class="well">
	<h2 class="page-header" style="margin-left: -5%;">Admin Area - Log File</h2>
</div>

<div class="container-fluid">
	<div class="well" style="padding: 1% 5%;">
		<div class="row input-group disp">
			<div class="input-group-addon">(</div>
			<a data-toggle="modal" data-target="#loginModal">
				<button class="btn btn-danger btn-group-justified"><span class="glyphicon glyphicon-remove"></span> Clear Log file</button>
			</a>
			<div class="input-group-addon">)</div>
		</div>
	</div>


	<!-- modal login form -->

    <div id="loginModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="font-family: Georgia, serif;">Clear Log </h4>
                </div>
                <div class="modal-body">
                    <div class="panel-heading">
                        <h2 class="panel-title">Are you sure you want to clear log file?</h2>
                    </div>           
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Dismiss</button>
                    <a href="logfile.php?clear=true"><button type="button" class="btn btn-success">Accept</button></a>
                </div>
            </div>
        </div>
    </div>


	<div class="jumbotron">
		<div class="row">

			<?php if( file_exists($log_file) && is_readable($log_file) && $handle = fopen($log_file, 'r')) : ?>

				<ul class="log-entries">
					<?php while( !feof($handle) ) : ?>
						<?php 
							$entry = fgets($handle); 
							if (trim($entry) != "")
							{
								echo "<li>{$entry}</li>";
							}
						?>
					<?php endwhile; ?>	
				</ul>
				<?php fclose($handle); ?>
			<?php else : ?>	
				<?= "<h4 class=\"modal-title\" style=\"font-family: Georgia, serif;\">
						<i class=\"fa fa-frown-o fa-3x\"></i>  Sorry! could not read from {$log_file}
					</h4>"; 
				?>
			<?php endif; ?>	
		</div>
	</div>
</div>

<?php include( ROOT_PATH . "incs/admin-footer.php"); ?>